<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add company fields to customers table
        Schema::table('customers', function (Blueprint $table): void {
            $table->string('tax_number')->nullable()->after('phone');
            $table->string('registration_number')->nullable()->after('tax_number');
            $table->string('email')->nullable()->after('registration_number');
        });

        // Step 2: Copy company data to customers that reference them
        $customers = DB::table('customers')->whereNotNull('company_id')->get();

        foreach ($customers as $customer) {
            $company = DB::table('companies')->where('id', $customer->company_id)->first();

            if ($company) {
                DB::table('customers')->where('id', $customer->id)->update([
                    'tax_number' => $company->tax_number,
                    'registration_number' => $company->registration_number,
                    'email' => $company->email,
                    'type' => 'company',
                ]);
            }
        }

        // Step 3: Drop company_id foreign key and column
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table): void {
            $table->foreignId('company_id')->nullable()->after('deleted_at')->constrained()->nullOnDelete();
        });

        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn(['tax_number', 'registration_number', 'email']);
        });
    }
};
