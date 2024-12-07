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
        Schema::create('tr_receives', function (Blueprint $table) {
            $table->id()->index();
            $table->bigInteger('sales_id')->index();
            $table->string('sales_type', 1)->comment('1 = sales, 2 = sales non')->index();
            $table->date('date')->index();
            $table->bigInteger('payment_method_id')->index();
            $table->decimal('amount', 18, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('is_status')->default('1')->index();
            $table->timestamps();
            $table->bigInteger('created_by')->nullable()->index();
            $table->bigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_receives');
    }
};
