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
        Schema::create('prm_role_menus', function (Blueprint $table) {
            $table->id()->index();
            $table->bigInteger('role_id')->index();
            $table->bigInteger('menu_id')->index();
            $table->string('is_show', 1)->default('1');
            $table->string('is_create', 1)->default('0');
            $table->string('is_update', 1)->default('0');
            $table->string('is_delete', 1)->default('0');
            $table->string('is_status', 1)->default('1')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prm_role_menus');
    }
};
