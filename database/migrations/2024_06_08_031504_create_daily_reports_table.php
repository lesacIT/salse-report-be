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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('user_id');
            $table->string('period_time');
            $table->integer('app_crc');
            $table->integer('loan_crc');
            $table->integer('app_plxs');
            $table->integer('loan_plxs');
            $table->float('amount_plxs');
            $table->float('amount_banca');
            $table->integer('loan_ctbs');
            $table->float('conver_banca');
            $table->float('conver_ctbs');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
