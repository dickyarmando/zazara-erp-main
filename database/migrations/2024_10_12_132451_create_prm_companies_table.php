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
        Schema::create('prm_companies', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name', 50)->index();
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('website', 50)->nullable();
            $table->string('picture', 100)->nullable();
            $table->string('is_status', 1)->default('1')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prm_companies');
    }
};
