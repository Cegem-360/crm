<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('calendar_event_id')->nullable()->after('completed_at');
        });

        Schema::table('interactions', function (Blueprint $table) {
            $table->string('calendar_event_id')->nullable()->after('email_recipient');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('calendar_event_id');
        });

        Schema::table('interactions', function (Blueprint $table) {
            $table->dropColumn('calendar_event_id');
        });
    }
};
