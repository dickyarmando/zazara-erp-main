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
        Schema::create('ms_suppliers', function (Blueprint $table) {
            $table->id()->index();
            $table->string('code', 20)->index();
            $table->string('name', 50)->index();
            $table->string('company_name', 50)->nullable()->index();
            $table->text('address')->nullable();
            $table->bigInteger('country_id')->default(0)->index();
            $table->bigInteger('state_id')->default(0)->index();
            $table->bigInteger('city_id')->default(0)->index();
            $table->integer('postal_code')->default(0);
            $table->string('email', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('is_status', 1)->default('1')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_suppliers');
    }
};
