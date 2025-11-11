<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servant_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->date('service_date');
            $table->enum('service_session', ['KU1', 'KU2', 'KU3'])->default('KU1');
            $table->time('service_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index untuk performa query
            $table->index(['service_date', 'service_session']);
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
