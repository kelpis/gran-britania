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
        Schema::create('availability_slots', function (Blueprint $table) {
            $table->id();
             $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['available','blocked'])->default('available');
            $table->timestamps();

            $table->unique(['date','start_time','end_time'], 'uniq_slot_interval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability_slots');
    }
};
