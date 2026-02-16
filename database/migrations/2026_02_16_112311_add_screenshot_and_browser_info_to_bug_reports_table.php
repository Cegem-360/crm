<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bug_reports', function (Blueprint $table): void {
            $table->json('screenshots')->nullable()->after('source');
            $table->string('browser_info')->nullable()->after('screenshots');
            $table->string('url')->nullable()->after('browser_info');
        });
    }

    public function down(): void
    {
        Schema::table('bug_reports', function (Blueprint $table): void {
            $table->dropColumn(['screenshots', 'browser_info', 'url']);
        });
    }
};
