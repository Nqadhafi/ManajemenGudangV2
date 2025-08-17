<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_divisi',
        'nama',
        'nik',
        'alamat',
        'nomor_hp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_karyawan');
    }
}