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
        Schema::create('tr_invoices', function (Blueprint $table) {
            $table->id()->index();
            $table->bigInteger('sales_id')->index();
            $table->string('number', 50)->index();
            $table->date('date')->index();
            $table->integer('due_termin')->comment('in days')->index();
            $table->date('due_date')->index();
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 18, 2)->default(0);
            $table->decimal('delivery_fee', 18, 2)->default(0);
            $table->decimal('discount', 18, 2)->default(0);
            $table->decimal('ppn', 18, 2)->default(0);
            $table->decimal('ppn_amount', 18, 2)->default(0);
            $table->decimal('total', 18, 2)->default(0);
            $table->decimal('payment', 18, 2)->default(0);
            $table->decimal('rest', 18, 2)->default(0);
            $table->timestamp('approved_at')->nullable()->index();
            $table->bigInteger('approved_by')->nullable()->index();
            $table->tinyInteger('is_receive')->default(0)->index();
            $table->tinyInteger('is_status')->default(1)->index();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('created_by')->nullable()->index();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_invoices');
    }
};
