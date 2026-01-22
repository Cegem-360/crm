<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $tables = [
        'orders',
        'quotes',
        'invoices',
        'tasks',
        'discounts',
        'bug_reports',
        'interactions',
        'opportunities',
    ];

    public function up(): void
    {
        $defaultTeamId = DB::table('teams')->value('id');

        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->foreignId('team_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->cascadeOnDelete();
            });

            DB::table($tableName)->whereNull('team_id')->update(['team_id' => $defaultTeamId]);
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropConstrainedForeignId('team_id');
            });
        }
    }
};
