<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('posisi')->nullable();
            $table->string('google_id')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_profil')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('tugas')->nullable();
            $table->enum('status', ['success', 'failed', 'pending']);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('admin');
    }
};
