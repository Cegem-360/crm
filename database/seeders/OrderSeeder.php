<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Team;
use Illuminate\Database\Seeder;

final class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $team = Team::query()->first();

        if (! $team) {
            return;
        }

        $customers = Customer::query()->where('team_id', $team->id)->get();
        $products = Product::query()->where('team_id', $team->id)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($customers->take(10) as $customer) {
            Order::factory()
                ->for($team)
                ->for($customer)
                ->hasOrderItems(3, [
                    'product_id' => fn () => $products->random()->id,
                ])
                ->create();
        }
    }
}
