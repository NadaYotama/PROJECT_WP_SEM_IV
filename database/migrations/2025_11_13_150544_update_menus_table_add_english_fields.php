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
        Schema::table('menus', function (Blueprint $table) {
            $table->renameColumn('nama', 'name');
            $table->renameColumn('deskripsi', 'description');
            $table->renameColumn('harga', 'price');
            $table->renameColumn('kategori', 'category');
            $table->renameColumn('gambar', 'image');
            $table->renameColumn('status', 'is_available');
            $table->boolean('is_popular')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->renameColumn('name', 'nama');
            $table->renameColumn('description', 'deskripsi');
            $table->renameColumn('price', 'harga');
            $table->renameColumn('category', 'kategori');
            $table->renameColumn('image', 'gambar');
            $table->renameColumn('is_available', 'status');
            $table->dropColumn('is_popular');
        });
    }
};
