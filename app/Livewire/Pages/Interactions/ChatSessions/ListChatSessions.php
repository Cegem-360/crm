<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Interactions\ChatSessions;

use App\Enums\ChatSessionStatus;
use App\Models\ChatSession;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListChatSessions extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'last_message_at';

    #[Url]
    public string $sortDir = 'desc';

    #[Url]
    public int $perPage = 10;

    #[Url]
    public string $status = '';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }

        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.interactions.chat-sessions.list-chat-sessions', [
            'chatSessions' => $this->getChatSessions(),
            'statuses' => ChatSessionStatus::cases(),
        ]);
    }

    private function getChatSessions(): LengthAwarePaginator
    {
        return ChatSession::query()
            ->with(['customer', 'user'])
            ->withCount('messages')
            ->when($this->search !== '', function ($query): void {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search): void {
                    $q->whereHas('customer', function ($customerQuery) use ($search): void {
                        $customerQuery->where('name', 'like', $search);
                    })
                        ->orWhereHas('user', function ($userQuery) use ($search): void {
                            $userQuery->where('name', 'like', $search);
                        });
                });
            })
            ->when($this->status !== '', function ($query): void {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
