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
        Schema::create('peserta_acara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acara_id_fk')->constrained('acara')->onDelete('cascade');
            $table->foreignId('warga_id_fk')->constrained('warga')->onDelete('cascade');
            $table->enum('status_kehadiran', ['hadir','tidak_hadir','belum_konfirmasi']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_acara');
    }
};
