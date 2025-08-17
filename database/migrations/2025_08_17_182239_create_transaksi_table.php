<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->unsignedBigInteger('id_barang');
            $table->unsignedBigInteger('id_karyawan');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_transaksi')->useCurrent();
            
            // Foreign key constraints
            $table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('id_karyawan')->references('id')->on('karyawan')->onDelete('cascade');
            
            $table->index(['tanggal_transaksi']);
            $table->index(['id_barang']);
            $table->index(['id_karyawan']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}