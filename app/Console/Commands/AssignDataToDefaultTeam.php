<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Company;
use App\Models\Customer;
use App\Models\EmailTemplate;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Team;
use App\Models\User;
use App\Models\WorkflowConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

final class AssignDataToDefaultTeam extends Command
{
    protected $signature = 'app:assign-data-to-default-team
                            {--team-name=Default : The name of the default team}';

    protected $description = 'Assign all existing records without team_id to a default team';

    public function handle(): int
    {
        $teamName = $this->option('team-name');

        $team = Team::query()->firstOrCreate(
            ['slug' => Str::slug($teamName)],
            ['name' => $teamName]
        );

        $this->info(sprintf('Using team: %s (ID: %s)', $team->name, $team->id));

        $models = [
            Customer::class,
            Company::class,
            Product::class,
            ProductCategory::class,
            EmailTemplate::class,
            Campaign::class,
            WorkflowConfig::class,
        ];

        foreach ($models as $modelClass) {
            $updated = $modelClass::query()
                ->whereNull('team_id')
                ->update(['team_id' => $team->id]);

            $modelName = class_basename($modelClass);
            $this->line(sprintf('  %s: %d records updated', $modelName, $updated));
        }

        $usersWithoutTeams = User::query()
            ->whereDoesntHave('teams')
            ->get();

        foreach ($usersWithoutTeams as $user) {
            $user->teams()->attach($team);
        }

        $this->line('  Users assigned to team: '.$usersWithoutTeams->count());

        $this->newLine();
        $this->info('All existing data has been assigned to the default team.');

        return self::SUCCESS;
    }
}
