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
        Schema::create('daily_todo_details', function (Blueprint $table) {
            $table->id();
            $table->string('daily_todo_id');
            $table->string('time_slot_id');
            $table->string('daily_activity_id');
            $table->string('place');
            $table->string('detail');
            $table->string('finished')->default('null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_todo_details');
    }
};
