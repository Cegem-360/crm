<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE opportunities MODIFY stage ENUM('lead', 'qualified', 'proposal', 'negotiation', 'sended_quotation', 'sent_quotation', 'lost_quotation') DEFAULT 'lead'");
        }

        DB::table('opportunities')
            ->where('stage', 'sended_quotation')
            ->update(['stage' => 'sent_quotation']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE opportunities MODIFY stage ENUM('lead', 'qualified', 'proposal', 'negotiation', 'sent_quotation', 'lost_quotation') DEFAULT 'lead'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE opportunities MODIFY stage ENUM('lead', 'qualified', 'proposal', 'negotiation', 'sended_quotation', 'sent_quotation', 'lost_quotation') DEFAULT 'lead'");
        }

        DB::table('opportunities')
            ->where('stage', 'sent_quotation')
            ->update(['stage' => 'sended_quotation']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE opportunities MODIFY stage ENUM('lead', 'qualified', 'proposal', 'negotiation', 'sended_quotation', 'lost_quotation') DEFAULT 'lead'");
        }
    }
};
