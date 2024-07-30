<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('automakers', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name')->unique();
            $table->string('date');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('automakers');
    }
    

    
   
};
