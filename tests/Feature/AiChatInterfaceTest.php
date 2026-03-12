<?php

declare(strict_types=1);

use App\Livewire\Ai\AiChatInterface;
use App\Models\AiUsageLog;
use App\Models\TeamSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    actingAs($this->user);
    $this->team = setUpFilamentTenant($this->user);

    config(['ai.providers.gemini.key' => 'test-api-key']);
});

it('can render the AI chat interface', function (): void {
    Livewire::test(AiChatInterface::class)
        ->assertSuccessful()
        ->assertSee(__('AI Assistant'));
});

it('validates that message is required', function (): void {
    Livewire::test(AiChatInterface::class)
        ->set('message', '')
        ->call('sendMessage')
        ->assertHasErrors(['message' => 'required']);
});

it('validates message max length', function (): void {
    Livewire::test(AiChatInterface::class)
        ->set('message', str_repeat('a', 5001))
        ->call('sendMessage')
        ->assertHasErrors(['message' => 'max']);
});

it('can start a new conversation', function (): void {
    Livewire::test(AiChatInterface::class)
        ->set('conversationId', 'some-id')
        ->set('messages', [['role' => 'user', 'content' => 'Hello']])
        ->call('startNewConversation')
        ->assertSet('conversationId', null)
        ->assertSet('messages', []);
});

it('can toggle the sidebar', function (): void {
    Livewire::test(AiChatInterface::class)
        ->assertSet('showSidebar', true)
        ->call('toggleSidebar')
        ->assertSet('showSidebar', false)
        ->call('toggleSidebar')
        ->assertSet('showSidebar', true);
});

it('resets to default model if invalid model is selected', function (): void {
    Livewire::test(AiChatInterface::class)
        ->set('selectedModel', 'invalid-model')
        ->set('message', 'Hello')
        ->call('sendMessage')
        ->assertSet('selectedModel', 'gemini-2.5-flash');
});

it('shows rate limit message when too many attempts', function (): void {
    $rateLimitKey = 'ai-chat:'.$this->user->id;

    for ($i = 0; $i < 10; $i++) {
        RateLimiter::hit($rateLimitKey, decaySeconds: 60);
    }

    Livewire::test(AiChatInterface::class)
        ->set('message', 'Hello')
        ->call('sendMessage')
        ->assertSee(__('Too many messages. Please wait a moment before trying again.'));
});

it('prevents loading a conversation owned by another user', function (): void {
    $otherUser = User::factory()->create();

    $conversationId = fake()->uuid();
    DB::table('agent_conversations')->insert([
        'id' => $conversationId,
        'user_id' => $otherUser->id,
        'title' => 'Other user conversation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Livewire::test(AiChatInterface::class)
        ->call('loadConversation', $conversationId)
        ->assertSet('conversationId', null)
        ->assertSet('messages', []);
});

it('prevents deleting a conversation owned by another user', function (): void {
    $otherUser = User::factory()->create();

    $conversationId = fake()->uuid();
    DB::table('agent_conversations')->insert([
        'id' => $conversationId,
        'user_id' => $otherUser->id,
        'title' => 'Other user conversation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Livewire::test(AiChatInterface::class)
        ->call('deleteConversation', $conversationId);

    expect(DB::table('agent_conversations')->where('id', $conversationId)->exists())->toBeTrue();
});

it('can load a conversation owned by the current user', function (): void {
    $conversationId = fake()->uuid();
    DB::table('agent_conversations')->insert([
        'id' => $conversationId,
        'user_id' => $this->user->id,
        'title' => 'My conversation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('agent_conversation_messages')->insert([
        'id' => fake()->uuid(),
        'conversation_id' => $conversationId,
        'agent' => 'crm-assistant',
        'role' => 'user',
        'content' => 'Hello there',
        'attachments' => '[]',
        'tool_calls' => '[]',
        'tool_results' => '[]',
        'usage' => '{}',
        'meta' => '{}',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('agent_conversation_messages')->insert([
        'id' => fake()->uuid(),
        'conversation_id' => $conversationId,
        'agent' => 'crm-assistant',
        'role' => 'assistant',
        'content' => 'Hi! How can I help?',
        'attachments' => '[]',
        'tool_calls' => '[]',
        'tool_results' => '[]',
        'usage' => '{}',
        'meta' => '{}',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Livewire::test(AiChatInterface::class)
        ->call('loadConversation', $conversationId)
        ->assertSet('conversationId', $conversationId)
        ->assertCount('messages', 2);
});

it('can delete a conversation owned by the current user', function (): void {
    $conversationId = fake()->uuid();
    DB::table('agent_conversations')->insert([
        'id' => $conversationId,
        'user_id' => $this->user->id,
        'title' => 'My conversation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('agent_conversation_messages')->insert([
        'id' => fake()->uuid(),
        'conversation_id' => $conversationId,
        'agent' => 'crm-assistant',
        'role' => 'user',
        'content' => 'Hello',
        'attachments' => '[]',
        'tool_calls' => '[]',
        'tool_results' => '[]',
        'usage' => '{}',
        'meta' => '{}',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Livewire::test(AiChatInterface::class)
        ->set('conversationId', $conversationId)
        ->call('deleteConversation', $conversationId)
        ->assertSet('conversationId', null)
        ->assertSet('messages', []);

    expect(DB::table('agent_conversations')->where('id', $conversationId)->exists())->toBeFalse();
    expect(DB::table('agent_conversation_messages')->where('conversation_id', $conversationId)->exists())->toBeFalse();
});

it('shows missing API key message when no key is configured', function (): void {
    config(['ai.providers.gemini.key' => null]);

    $component = Livewire::test(AiChatInterface::class)
        ->set('message', 'Hello')
        ->call('sendMessage');

    $messages = $component->get('messages');
    $lastMessage = end($messages);

    expect($lastMessage['role'])->toBe('assistant');
    expect($lastMessage['content'])->toContain('No AI API key configured');
    expect($lastMessage['content'])->toContain('manage-team-settings');
});

it('shows token limit exceeded message when team limit is reached', function (): void {
    TeamSetting::factory()->create([
        'team_id' => $this->team->id,
        'ai_monthly_token_limit' => 1000,
    ]);

    AiUsageLog::query()->create([
        'team_id' => $this->team->id,
        'user_id' => $this->user->id,
        'conversation_id' => null,
        'model' => 'gemini-2.5-flash',
        'input_tokens' => 600,
        'output_tokens' => 500,
    ]);

    $component = Livewire::test(AiChatInterface::class)
        ->set('message', 'Hello')
        ->call('sendMessage');

    $messages = $component->get('messages');
    $lastMessage = end($messages);

    expect($lastMessage['role'])->toBe('assistant');
    expect($lastMessage['content'])->toBe(__('Your team has reached the monthly AI token limit. Please contact your administrator.'));
});

it('returns correct available models', function (): void {
    /** @var AiChatInterface $component */
    $component = Livewire::test(AiChatInterface::class)->instance();
    $models = $component->availableModels();

    expect($models)->toHaveKeys(['gemini-2.5-flash', 'gemini-2.5-pro', 'gemini-2.0-flash']);
});

it('computes token usage correctly', function (): void {
    TeamSetting::factory()->create([
        'team_id' => $this->team->id,
        'ai_monthly_token_limit' => 10000,
    ]);

    AiUsageLog::query()->create([
        'team_id' => $this->team->id,
        'user_id' => $this->user->id,
        'conversation_id' => null,
        'model' => 'gemini-2.5-flash',
        'input_tokens' => 2000,
        'output_tokens' => 3000,
    ]);

    $component = Livewire::test(AiChatInterface::class);

    $tokenUsage = $component->get('tokenUsage');

    expect($tokenUsage['used'])->toBe(5000);
    expect($tokenUsage['limit'])->toBe(10000);
    expect($tokenUsage['percentage'])->toBe(50);
});

it('sanitizes HTML tags from user input', function (): void {
    Livewire::test(AiChatInterface::class)
        ->set('message', '<script>alert("xss")</script>Hello')
        ->call('sendMessage')
        ->assertSet('messages.0.content', 'alert("xss")Hello');
});
