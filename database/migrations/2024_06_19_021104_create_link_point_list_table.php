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
        Schema::create('link_point_list', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('user_id');
            $table->string('name_dlk');
            $table->integer('local_province_id');
            $table->integer('local_ward_id');
            $table->integer('local_district_id');
            $table->string('address_dlk');
            $table->integer('list_of_types_dlk_id');
            $table->string('full_name_of_representative');
            $table->integer('list_of_items_dlk_id');
            $table->string('image');
            $table->string('locate');
            $table->string('status_dlk');
            $table->string('advise_crc');
            $table->string('eligible_crc');
            $table->string('go_to_app_crc');
            $table->string('loan_crc');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_point_list');
    }
};
