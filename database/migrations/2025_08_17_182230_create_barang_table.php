<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangTable extends Migration
{
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->integer('stok')->default(0);
            $table->string('satuan');
            $table->integer('stok_minimum')->default(0);
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_supplier');
            $table->timestamps();
            
            $table->foreign('id_kategori')->references('id')->on('kategori')->onDelete('cascade');
            $table->foreign('id_supplier')->references('id')->on('supplier')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang');
    }
}