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
        Schema::create('tr_purchase_non_details', function (Blueprint $table) {
            $table->id()->index();
            $table->bigInteger('purchase_non_id')->index();
            $table->string('product_name', 100)->index();
            $table->string('unit_name', 50)->index();
            $table->decimal('qty', 18, 2)->default(0);
            $table->decimal('rate', 18, 2)->default(0);
            $table->decimal('amount', 18, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_purchase_non_details');
    }
};
