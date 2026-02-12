<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Company;
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

        // Create companies for the team
        $companies = Company::factory(15)->create([
            'team_id' => $team->id,
        ]);

        // Create customers with related data for the team
        // Some customers belong to companies, some are individuals
        Customer::factory(50)
            ->for($team)
            ->state(fn (): array => [
                'company_id' => fake()->boolean(60) ? $companies->random()->id : null,
            ])
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

        $this->call([
            OrderSeeder::class,
        ]);

        $this->command->info('Database seeded successfully!');
    }
}
