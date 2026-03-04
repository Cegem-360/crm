<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropUnique(['unique_identifier']);
            $table->unique(['unique_identifier', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropUnique(['unique_identifier', 'team_id']);
            $table->unique(['unique_identifier']);
        });
    }
};
