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
        Schema::create('prm_menus', function (Blueprint $table) {
            $table->id()->index();
            $table->bigInteger('parent_id')->default(0)->index();
            $table->string('name', 50);
            $table->string('icon', 50)->nullable();
            $table->string('action', 100)->default('#');
            $table->integer('seq')->default(1)->index();
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
        Schema::dropIfExists('prm_menus');
    }
};
