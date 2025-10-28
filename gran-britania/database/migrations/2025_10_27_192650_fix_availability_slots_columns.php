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
        Schema::table('availability_slots', function (Blueprint $table) {
            // Añade las columnas si faltan
            if (!Schema::hasColumn('availability_slots', 'date')) {
                $table->date('date')->after('id');
            }
            if (!Schema::hasColumn('availability_slots', 'start_time')) {
                $table->time('start_time')->after('date');
            }
            if (!Schema::hasColumn('availability_slots', 'end_time')) {
                $table->time('end_time')->after('start_time');
            }
            if (!Schema::hasColumn('availability_slots', 'status')) {
                $table->enum('status', ['available','blocked'])->default('available')->after('end_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('availability_slots', function (Blueprint $table) {
             // Opcional: elimina las columnas añadidas en up()
            if (Schema::hasColumn('availability_slots', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('availability_slots', 'end_time')) {
                $table->dropColumn('end_time');
            }
            if (Schema::hasColumn('availability_slots', 'start_time')) {
                $table->dropColumn('start_time');
            }
            if (Schema::hasColumn('availability_slots', 'date')) {
                $table->dropColumn('date');
            }
        });
    }
};
