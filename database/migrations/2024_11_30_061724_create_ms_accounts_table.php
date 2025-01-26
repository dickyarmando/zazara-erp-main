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
            $table->bigInteger('category_account_id')->index();
            $table->string('account_type', 2)->nullable()->comment('db = debit, cr = credit')->index();
            $table->string('debit', 1)->nullable()->index();
            $table->string('credit', 1)->nullable()->index();
            $table->decimal('opening_balance', 18, 2)->default(0);
            $table->bigInteger('parent_code')->nullable()->index();
            $table->string('level')->default('G')->comment('G = group, D = detail')->index();
            $table->string('is_status', 1)->default('1')->index();
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
        Schema::dropIfExists('ms_accounts');
    }
};
