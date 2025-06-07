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
        Schema::create('data_diri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admin');
            $table->string('NIK');
            $table->string('kontak');
            $table->string('alamat');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('data_diri');
    }
};
