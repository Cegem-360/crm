<?php

declare(strict_types=1);

use App\Models\CustomerContact;
use App\Models\EmailTemplate;
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
        Schema::table('interactions', function (Blueprint $table): void {
            // Make customer_id nullable
            $table->foreignId('customer_id')->nullable()->change();

            // Add new fields
            $table->foreignIdFor(CustomerContact::class)->nullable()->after('customer_id')->constrained()->nullOnDelete();
            $table->foreignIdFor(EmailTemplate::class)->nullable()->after('type')->constrained()->nullOnDelete();
            $table->string('category')->default('general')->after('type');
            $table->string('channel')->nullable()->after('category');
            $table->string('direction')->nullable()->after('channel');
            $table->string('status')->default('completed')->after('direction');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interactions', function (Blueprint $table): void {
            $table->dropSoftDeletes();
            $table->dropConstrainedForeignIdFor(EmailTemplate::class);
            $table->dropConstrainedForeignIdFor(CustomerContact::class);
            $table->dropColumn(['category', 'channel', 'direction', 'status']);

            // Restore customer_id as required
            $table->foreignId('customer_id')->nullable(false)->change();
        });
    }
};
