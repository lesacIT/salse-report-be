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
        Schema::create('daily_targets', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('user_id');
            $table->string('crc_app');
            $table->string('crc_loan');
            $table->string('plxs_app');
            $table->string('plxs_loan');
            $table->string('amount_plxs');
            $table->string('amount_banca');
            $table->string('loan_ctbs');
            $table->string('convert_banca');
            $table->string('convert_ctbs');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_targets');
    }
};
