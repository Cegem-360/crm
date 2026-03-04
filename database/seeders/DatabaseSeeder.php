<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerAttribute;
use App\Models\CustomerContact;
use App\Models\Opportunity;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
        ]);

        // Create the main team
        $team = Team::factory()->create([
            'name' => 'Demo Company',
            'slug' => 'demo-company',
        ]);

        // Create admin user and attach to team
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole(Role::Admin);
        $admin->teams()->attach($team);

        // Create additional users and attach to team
        $users = User::factory(5)->create();
        $team->users()->attach($users);

        // Create product categories for the team
        $categories = ProductCategory::factory(5)->create([
            'team_id' => $team->id,
        ]);

        // Create products for the team
        Product::factory(20)->create([
            'team_id' => $team->id,
            'category_id' => fn () => $categories->random()->id,
        ]);

        // Create customers with related data for the team
        Customer::factory(50)
            ->for($team)
            ->has(
                CustomerContact::factory(2)
                    ->state(['team_id' => $team->id]),
                'contacts'
            )
            ->has(
                CustomerAddress::factory()->billing()->default(),
                'addresses'
            )
            ->has(
                CustomerAddress::factory()->shipping(),
                'addresses'
            )
            ->has(CustomerAttribute::factory(3), 'attributes')
            ->has(
                Opportunity::factory(2)
                    ->state([
                        'team_id' => $team->id,
                        'assigned_to' => fn () => $users->random()->id,
                    ]),
                'opportunities'
            )
            ->create();

        // Create second team for testing tenant isolation
        $team2 = Team::factory()->create([
            'name' => 'Teszt Kft.',
            'slug' => 'teszt-kft',
        ]);

        // Attach admin to second team too (for testing via team switcher)
        $admin->teams()->attach($team2);

        // Create users exclusively for the second team
        $team2Users = User::factory(3)->create();
        $team2->users()->attach($team2Users);

        // Create product categories for the second team
        $team2Categories = ProductCategory::factory(3)->create([
            'team_id' => $team2->id,
        ]);

        // Create products for the second team
        Product::factory(10)->create([
            'team_id' => $team2->id,
            'category_id' => fn () => $team2Categories->random()->id,
        ]);

        // Create customers with related data for the second team
        Customer::factory(15)
            ->for($team2)
            ->has(
                CustomerContact::factory(2)
                    ->state(['team_id' => $team2->id]),
                'contacts'
            )
            ->has(
                CustomerAddress::factory()->billing()->default(),
                'addresses'
            )
            ->has(
                CustomerAddress::factory()->shipping(),
                'addresses'
            )
            ->has(CustomerAttribute::factory(3), 'attributes')
            ->has(
                Opportunity::factory(2)
                    ->state([
                        'team_id' => $team2->id,
                        'assigned_to' => fn () => $team2Users->random()->id,
                    ]),
                'opportunities'
            )
            ->create();

        $this->call([
            OrderSeeder::class,
        ]);

        $this->command->info('Database seeded successfully!');
    }
}
