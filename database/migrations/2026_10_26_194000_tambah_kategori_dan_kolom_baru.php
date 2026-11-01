<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::table('komponens', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable()->after('id')
                ->constrained('kategoris')->nullOnDelete();
            $table->enum('jenis_arus', ['AC', 'DC', 'AC/DC'])->nullable()->after('nama');
        });

        Schema::table('kerusakans', function (Blueprint $table) {
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi'])->default('Sedang')->after('nama');
        });

        Schema::table('gejalas', function (Blueprint $table) {
            $table->string('pertanyaan')->nullable()->after('nama');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email');
        });

        Schema::create('rekomendasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerusakan_id')->constrained('kerusakans')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan')->default(1);
            $table->text('langkah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasis');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        Schema::table('gejalas', function (Blueprint $table) {
            $table->dropColumn('pertanyaan');
        });
        Schema::table('kerusakans', function (Blueprint $table) {
            $table->dropColumn('prioritas');
        });
        Schema::table('komponens', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_id');
            $table->dropColumn('jenis_arus');
        });

        Schema::dropIfExists('kategoris');
    }
};