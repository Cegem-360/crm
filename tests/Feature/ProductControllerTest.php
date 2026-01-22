<?php

declare(strict_types=1);

use App\Enums\Permission as PermissionEnum;
use App\Jobs\SendProductWebhook;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function (): void {
    app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    Queue::fake();
    $this->team = Team::factory()->create();
});

describe('Product API Index', function (): void {
    it('returns paginated products for authorized user', function (): void {
        $user = User::factory()->create();
        Permission::create(['name' => PermissionEnum::ViewAnyProduct]);
        $user->givePermissionTo(PermissionEnum::ViewAnyProduct);

        Product::factory()->count(3)->create(['is_active' => true]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/products');

        $response->assertSuccessful()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'sku', 'unit_price', 'tax_rate', 'is_active'],
                ],
                'meta',
                'links',
            ]);
    });

    it('filters inactive products by default', function (): void {
        $user = User::factory()->create();
        Permission::create(['name' => PermissionEnum::ViewAnyProduct]);
        $user->givePermissionTo(PermissionEnum::ViewAnyProduct);

        Product::factory()->count(2)->create(['is_active' => true]);
        Product::factory()->inactive()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/products');

        $response->assertSuccessful()
            ->assertJsonCount(2, 'data');
    });

    it('includes inactive products when requested', function (): void {
        $user = User::factory()->create();
        Permission::create(['name' => PermissionEnum::ViewAnyProduct]);
        $user->givePermissionTo(PermissionEnum::ViewAnyProduct);

        Product::factory()->count(2)->create(['is_active' => true]);
        Product::factory()->inactive()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/products?include_inactive=1');

        $response->assertSuccessful()
            ->assertJsonCount(3, 'data');
    });

    it('denies access for unauthorized user', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/products');

        $response->assertForbidden();
    });

    it('returns 401 for unauthenticated request', function (): void {
        $response = $this->getJson('/api/v1/products');

        $response->assertUnauthorized();
    });
});

describe('Product API Store', function (): void {
    it('creates a product for authorized user', function (): void {
        $user = User::factory()->create();
        $user->teams()->attach($this->team);
        Permission::create(['name' => PermissionEnum::CreateProduct]);
        $user->givePermissionTo(PermissionEnum::CreateProduct);

        $productData = [
            'name' => 'Test Product',
            'sku' => 'TEST-001',
            'description' => 'Test description',
            'unit_price' => 1500.00,
            'tax_rate' => 27.00,
            'is_active' => true,
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/products', $productData);

        $response->assertSuccessful()
            ->assertJsonPath('data.name', 'Test Product')
            ->assertJsonPath('data.sku', 'TEST-001');

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'sku' => 'TEST-001',
            'team_id' => $this->team->id,
        ]);
    });

    it('dispatches webhook job on product creation for current user', function (): void {
        $user = User::factory()->create();
        $user->teams()->attach($this->team);
        Permission::create(['name' => PermissionEnum::CreateProduct]);
        $user->givePermissionTo(PermissionEnum::CreateProduct);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/products', [
                'name' => 'Webhook Test Product',
                'sku' => 'WEBHOOK-001',
                'unit_price' => 1000.00,
                'tax_rate' => 27.00,
            ]);

        Queue::assertPushed(SendProductWebhook::class, function ($job) use ($user) {
            return $job->event === 'created' && $job->user->id === $user->id;
        });
    });

    it('validates required fields', function (): void {
        $user = User::factory()->create();
        Permission::create(['name' => PermissionEnum::CreateProduct]);
        $user->givePermissionTo(PermissionEnum::CreateProduct);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/products', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'sku', 'unit_price', 'tax_rate']);
    });

    it('validates unique sku', function (): void {
        $user = User::factory()->create();
        Permission::create(['name' => PermissionEnum::CreateProduct]);
        $user->givePermissionTo(PermissionEnum::CreateProduct);

        Product::factory()->create(['sku' => 'EXISTING-SKU']);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/products', [
                'name' => 'Test Product',
                'sku' => 'EXISTING-SKU',
                'unit_price' => 1000.00,
                'tax_rate' => 27.00,
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['sku']);
    });

    it('denies access for unauthorized user', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/products', [
                'name' => 'Test Product',
                'sku' => 'TEST-001',
                'unit_price' => 1000.00,
                'tax_rate' => 27.00,
            ]);

        $response->assertForbidden();
    });
});

describe('Product API Show', function (): void {
    it('returns a product for authorized user', function (): void {
        $user = User::factory()->create();
        Permission::create(['name' => PermissionEnum::ViewProduct]);
        $user->givePermissionTo(PermissionEnum::ViewProduct);

        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/products/{$product->id}");

        $response->assertSuccessful()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.name', $product->name);
    });

    it('denies access for unauthorized user', function (): void {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/products/{$product->id}");

        $response->assertForbidden();
    });

    it('returns 404 for non-existent product', function (): void {
        $user = User::factory()->create();
        Permission::create(['name' => PermissionEnum::ViewProduct]);
        $user->givePermissionTo(PermissionEnum::ViewProduct);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/products/99999');

        $response->assertNotFound();
    });
});

describe('Product API Update', function (): void {
    it('updates a product for authorized user', function (): void {
        $user = User::factory()->create();
        Permission::create(['name' => PermissionEnum::UpdateProduct]);
        $user->givePermissionTo(PermissionEnum::UpdateProduct);

        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/products/{$product->id}", [
                'name' => 'Updated Name',
            ]);

        $response->assertSuccessful()
            ->assertJsonPath('data.name', 'Updated Name');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
        ]);
    });

    it('dispatches webhook job on product update for current user', function (): void {
        $user = User::factory()->create();
        Permission::create(['name' => PermissionEnum::UpdateProduct]);
        $user->givePermissionTo(PermissionEnum::UpdateProduct);

        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/products/{$product->id}", [
                'name' => 'Updated Name',
            ]);

        Queue::assertPushed(SendProductWebhook::class, function ($job) use ($user) {
            return $job->event === 'updated' && $job->user->id === $user->id;
        });
    });

    it('denies access for unauthorized user', function (): void {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/products/{$product->id}", [
                'name' => 'Updated Name',
            ]);

        $response->assertForbidden();
    });
});

describe('Product API Delete', function (): void {
    it('deletes a product for authorized user', function (): void {
        $user = User::factory()->create();
        Permission::create(['name' => PermissionEnum::DeleteProduct]);
        $user->givePermissionTo(PermissionEnum::DeleteProduct);

        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/products/{$product->id}");

        $response->assertSuccessful()
            ->assertJsonPath('message', 'Product deleted successfully');

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    });

    it('dispatches webhook job on product deletion for current user', function (): void {
        $user = User::factory()->create();
        Permission::create(['name' => PermissionEnum::DeleteProduct]);
        $user->givePermissionTo(PermissionEnum::DeleteProduct);

        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/products/{$product->id}");

        Queue::assertPushed(SendProductWebhook::class, function ($job) use ($user) {
            return $job->event === 'deleted' && $job->user->id === $user->id;
        });
    });

    it('denies access for unauthorized user', function (): void {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/products/{$product->id}");

        $response->assertForbidden();
    });
});
