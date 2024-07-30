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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('manager_id');
            $table->integer('organization_id');
            $table->integer('role_id');
            $table->integer('status');
            $table->string('username')->unique();
            $table->date('date_of_birth')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number');
            $table->string('identity_number');
            $table->string('avatar');
            $table->integer('id_local_province');
            $table->integer('id_local_district');
            $table->integer('id_local_ward');
            $table->char('path');
            $table->string('api_token');
            $table->timestamp('email_verified_at')->nullable();
            $table->date('date_start_working');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
