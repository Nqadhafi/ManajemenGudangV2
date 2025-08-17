<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanTable extends Migration
{
    public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_divisi');
            $table->string('nama');
            $table->string('nik')->unique();
            $table->text('alamat')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->timestamps();
            
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_divisi')->references('id')->on('divisi')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('karyawan');
    }
}