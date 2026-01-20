<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create companies table
        Schema::create('companies', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('tax_number')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Add company_id to customers
        Schema::table('customers', function (Blueprint $table): void {
            $table->foreignId('company_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        // 3. Migrate existing data - create companies from customers that have company data
        $customers = DB::table('customers')
            ->whereNotNull('tax_number')
            ->orWhereNotNull('registration_number')
            ->orWhere('email', '!=', '')
            ->get();

        foreach ($customers as $customer) {
            // Only create company if at least one company field has data
            if ($customer->tax_number || $customer->registration_number || $customer->email) {
                $companyId = DB::table('companies')->insertGetId([
                    'name' => $customer->name,
                    'tax_number' => $customer->tax_number,
                    'registration_number' => $customer->registration_number,
                    'email' => $customer->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('customers')
                    ->where('id', $customer->id)
                    ->update(['company_id' => $companyId]);
            }
        }

        // 4. Remove old columns from customers
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropColumn(['tax_number', 'registration_number', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Re-add columns to customers
        Schema::table('customers', function (Blueprint $table): void {
            $table->string('tax_number')->nullable()->after('type');
            $table->string('registration_number')->nullable()->after('tax_number');
            $table->string('email')->nullable()->after('registration_number');
        });

        // 2. Migrate data back
        $customers = DB::table('customers')
            ->whereNotNull('company_id')
            ->get();

        foreach ($customers as $customer) {
            $company = DB::table('companies')->where('id', $customer->company_id)->first();
            if ($company) {
                DB::table('customers')
                    ->where('id', $customer->id)
                    ->update([
                        'tax_number' => $company->tax_number,
                        'registration_number' => $company->registration_number,
                        'email' => $company->email,
                    ]);
            }
        }

        // 3. Remove company_id from customers
        Schema::table('customers', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('company_id');
        });

        // 4. Drop companies table
        Schema::dropIfExists('companies');
    }
};
