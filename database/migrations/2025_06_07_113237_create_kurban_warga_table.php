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
        Schema::create('kurban_warga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurban_id_fk')->constrained('kurban')->onDelete('cascade');
            $table->foreignId('warga_id_fk')->constrained('warga')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurban_warga');
    }
};
