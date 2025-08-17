<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'stok',
        'satuan',
        'stok_minimum',
        'id_kategori',
        'id_supplier'
    ];

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