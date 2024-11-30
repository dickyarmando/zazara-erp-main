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
        Schema::create('tr_general_ledgers', function (Blueprint $table) {
            $table->id()->index();
            $table->string('number', 50)->index();
            $table->date('date')->index();
            $table->string('reference', 50)->nullable()->index();
            $table->text('notes')->nullable();
            $table->decimal('total_debit', 18, 2)->default(0);
            $table->decimal('total_credit', 18, 2)->default(0);
            $table->string('is_status', 1)->default('1')->index();
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
        Schema::dropIfExists('tr_general_ledgers');
    }
};
