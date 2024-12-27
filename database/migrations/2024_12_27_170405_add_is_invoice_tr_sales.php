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
        Schema::table('tr_sales', function (Blueprint $table) {
            $table->string('is_invoice', 1)->default('0')->index()->after('sales_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tr_sales', function (Blueprint $table) {
            $table->dropColumn('is_invoice');
        });
    }
};
