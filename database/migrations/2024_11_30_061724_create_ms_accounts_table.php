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
        Schema::create('ms_accounts', function (Blueprint $table) {
            $table->id()->index();
            $table->string('code', 20)->index();
            $table->string('name', 100)->index();
            $table->integer('type')->comment('1 = bank, 2 = expanse, 3 = equity')->index();
            $table->decimal('opening_balance', 18, 2)->default(0);
            $table->string('type_balance', 2)->comment('db = debit, cr = credit')->index();
            $table->string('is_status', 1)->default('1')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_accounts');
    }
};
