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
        Schema::table('prm_role_menus', function (Blueprint $table) {
            $table->string('is_sales', 1)->default('0')->after('is_delete');
            $table->string('is_approved', 1)->default('0')->after('is_sales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prm_role_menus', function (Blueprint $table) {
            $table->dropColumn('is_sales');
            $table->dropColumn('is_approved');
        });
    }
};
