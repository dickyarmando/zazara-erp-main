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
            $table->decimal('dpp', 18, 2)->default(0)->after('subtotal');
            $table->decimal('dpp_amount', 18, 2)->default(0)->after('dpp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tr_sales', function (Blueprint $table) {
            $table->dropColumn('dpp');
            $table->dropColumn('dpp_amount');
        });
    }
};
