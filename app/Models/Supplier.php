<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';
    protected $fillable = [
        'nama_supplier',
        'no_hp_supplier',
        'alamat'
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_supplier');
    }
}