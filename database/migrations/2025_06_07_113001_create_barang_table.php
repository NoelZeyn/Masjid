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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('supplier')->onDelete('cascade');
            $table->string('nama_barang');
            $table->string('deskripsi');
            $table->integer('harga');
            $table->integer('stock');
            $table->binary('gambar')->nullable();
            $table->string('merk');
            $table->enum('kategori', ['A','B','C','D']);
            $table->enum('kondisi', ['bagus','rusak','habis','kotor','perbaikan']);
            $table->enum('keterangan', ['non-aktif','aktif','rusak','habis']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
