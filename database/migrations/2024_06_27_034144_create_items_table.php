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
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('jurusan_id');
            $table->unsignedBigInteger('image_id');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi');
            $table->enum('status', ['tersedia', 'dipinjam', 'maintenance'])->default('tersedia');

            $table->foreign('kategori_id')->references('id')->on('kategori')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('image_id')->references('id')->on('image')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('jurusan_id')->references('id')->on('jurusan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
