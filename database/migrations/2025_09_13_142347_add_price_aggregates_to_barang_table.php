<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceAggregatesToBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang', function (Blueprint $table) {
            // total penjumlahan harga_satuan dari transaksi MASUK yang berharga
            $table->decimal('sum_harga_masuk', 18, 2)->default(0)->after('stok_minimum');
            // jumlah transaksi MASUK yang berharga
            $table->unsignedInteger('cnt_harga_masuk')->default(0)->after('sum_harga_masuk');
            // jika kolom avg_harga belum kamu tambahkan sebelumnya, aktifkan baris di bawah ini:
            // $table->decimal('avg_harga', 15, 2)->nullable()->after('cnt_harga_masuk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['sum_harga_masuk', 'cnt_harga_masuk'/*, 'avg_harga'*/]);
        });
    }
}
