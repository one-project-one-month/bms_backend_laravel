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
        Schema::create('deposit_withdraws', function (Blueprint $table) {
            $table->id();
            $table->string('process');
            $table->string('accountNo');
            $table->decimal('amount',19,2);
            $table->date('date');
            $table->time('time');
            $table->timestamps();
            $table->unsignedInteger('adminId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depoist_withdraws');
    }
};
