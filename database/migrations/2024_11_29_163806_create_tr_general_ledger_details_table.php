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
        Schema::create('tr_general_ledger_details', function (Blueprint $table) {
            $table->id()->index();
            $table->bigInteger('general_ledger_id')->index();
            $table->bigInteger('account_id')->index();
            $table->string('type', 2)->comment('db = debit, cr = credit')->index();
            $table->decimal('amount', 18, 2)->default(0);
            $table->string('is_status', 1)->default('1')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_general_ledger_details');
    }
};
