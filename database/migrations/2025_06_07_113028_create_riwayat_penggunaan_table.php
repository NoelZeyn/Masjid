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
        Schema::create('riwayat_penggunaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id_fk')->constrained('barang')->onDelete('cascade');
            $table->date('tanggal_pemijaman')->nullable();
            $table->string('deskripsi_penggunaan')->nullable();
            $table->date('tanggal_pengembalian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_penggunaan');
    }
};
