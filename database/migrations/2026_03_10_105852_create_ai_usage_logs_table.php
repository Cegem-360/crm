<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_usage_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->string('conversation_id', 36)->nullable()->index();
            $table->string('model', 100);
            $table->unsignedInteger('input_tokens')->default(0);
            $table->unsignedInteger('output_tokens')->default(0);
            $table->timestamps();

            $table->index(['team_id', 'created_at']);
        });

        Schema::table('team_settings', function (Blueprint $table): void {
            $table->unsignedInteger('ai_monthly_token_limit')->default(100000)->after('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_usage_logs');

        Schema::table('team_settings', function (Blueprint $table): void {
            $table->dropColumn('ai_monthly_token_limit');
        });
    }
};
