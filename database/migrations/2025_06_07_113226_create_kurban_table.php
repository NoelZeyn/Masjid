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
        Schema::create('kurban', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_hewan', ['kambing', 'sapi', 'domba', 'unta']);
            $table->integer('jumlah');
            $table->enum('status', ['belum_disembelih', 'sudah_disembelih']);
            $table->foreignId('admin_id')->nullable()->constrained('admin')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurban');
    }
};
