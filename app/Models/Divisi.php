<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    // Tambahkan ini untuk memperbaiki nama tabel
    protected $table = 'divisi';

    protected $fillable = [
        'nama'
    ];

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class, 'id_divisi');
    }
}