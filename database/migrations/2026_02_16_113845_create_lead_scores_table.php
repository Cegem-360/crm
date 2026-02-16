<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_scores', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->integer('interaction_score')->default(0);
            $table->integer('recency_score')->default(0);
            $table->integer('opportunity_score')->default(0);
            $table->integer('engagement_score')->default(0);
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'customer_id']);
            $table->index(['team_id', 'score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_scores');
    }
};
