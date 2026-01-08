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
        Schema::create('t_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Contoh kolom: Nama kategori
            $table->string('slug')->unique(); // Contoh kolom: Slug untuk URL
            $table->text('description')->nullable(); // Contoh kolom: Deskripsi
            $table->timestamps(); // Membuat kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_categories');
    }
};