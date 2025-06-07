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
        Schema::create('infaq', function (Blueprint $table) {
            $table->id();
            $table->string('nama_infaq');
            $table->foreignId('warga_id_fk')->constrained('warga')->onDelete('cascade');
            $table->string('tanggal_pemberian');
            $table->enum('kategori_infaq', ['zakat', 'kafarat', 'nazar', 'jihad', 'infaq_membantu', 'infaq_bencana', 'infaq_kemanusiaan', 'mubah', 'haram']);
            $table->string('jumlah');
            $table->string('satuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infaq');
    }
};
