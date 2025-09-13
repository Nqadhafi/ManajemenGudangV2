<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';
    protected $fillable = [
        'nama_barang',
        'stok',
        'satuan',
        'stok_minimum',
        'id_kategori',
        'id_supplier',
        'avg_harga',         // cache rata-rata per transaksi (nullable)
        'sum_harga_masuk',   // agregat jumlah harga
        'cnt_harga_masuk',   // agregat jumlah transaksi berharga
    ];

        protected $casts = [
        'avg_harga'        => 'decimal:2',
        'sum_harga_masuk'  => 'decimal:2',
        'cnt_harga_masuk'  => 'integer',
    ];

        public function getNilaiAsetAttribute()
    {
return is_null($this->avg_harga) ? null : (float)$this->avg_harga * (int)$this->stok;
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_barang');
    }

    public function getStokStatusAttribute()
    {
        if ($this->stok <= $this->stok_minimum) {
            return 'danger';
        } elseif ($this->stok <= ($this->stok_minimum * 1.5)) {
            return 'warning';
        } else {
            return 'success';
        }
    }
}