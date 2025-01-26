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
        Schema::create('ms_intensive_user', function (Blueprint $table) {
            $table->id()->index();
            $table->bigInteger('user_id')->index();
            $table->decimal('target_amount', 18, 2)->default(0);
            $table->integer('up')->default(0);
            $table->integer('down')->default(0);
            $table->tinyInteger('is_status')->default(1)->index();
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
        Schema::dropIfExists('ms_intensive_user');
    }
};
