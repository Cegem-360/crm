<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('opportunities')
            ->where('stage', 'sended_quotation')
            ->update(['stage' => 'sent_quotation']);
    }

    public function down(): void
    {
        DB::table('opportunities')
            ->where('stage', 'sent_quotation')
            ->update(['stage' => 'sended_quotation']);
    }
};
