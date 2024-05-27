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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('adminCode');
            $table->string('username');
            $table->string('fullName');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('isDelete')->default(0);
            $table->boolean('isDeactivate')->default(0);
            $table->enum('role',['admin', 'employee'])->default('employee');
            $table->string('managerId')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
