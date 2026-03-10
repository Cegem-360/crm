<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_settings', function (Blueprint $table): void {
            $table->text('gemini_api_key')->nullable()->after('ai_monthly_token_limit');
        });
    }

    public function down(): void
    {
        Schema::table('team_settings', function (Blueprint $table): void {
            $table->dropColumn('gemini_api_key');
        });
    }
};
