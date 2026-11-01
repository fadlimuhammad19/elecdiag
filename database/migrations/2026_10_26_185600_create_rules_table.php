<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerusakan_id')->constrained('kerusakans')->cascadeOnDelete();
            $table->foreignId('gejala_id')->constrained('gejalas')->cascadeOnDelete();
            $table->decimal('cf_pakar', 3, 2);
            $table->timestamps();

            $table->unique(['kerusakan_id', 'gejala_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};