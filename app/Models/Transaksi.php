<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $fillable = [
        'jenis_transaksi',
        'id_barang',
        'id_karyawan',
        'jumlah',
        'keterangan',
        'harga_satuan'
    ];

    protected $dates = [
        'tanggal_transaksi'
    ];
    protected $casts = [
        'harga_satuan' => 'decimal:2',
    ];
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}