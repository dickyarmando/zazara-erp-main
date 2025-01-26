<?php

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
        Schema::table('tr_invoices_nons', function (Blueprint $table) {
            $table->string('is_posting', 1)->default('0')->index()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tr_invoices_nons', function (Blueprint $table) {
            $table->dropColumn('is_posting');
        });
    }
};
