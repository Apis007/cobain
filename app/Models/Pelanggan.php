<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use SoftDeletes;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'alamat',
        'status',
        'paket',
    ];

    // Relasi ke model Redaman
    public function redaman()
    {
        return $this->hasMany(Redaman::class, 'id_pelanggan', 'id'); // Menghubungkan `id_pelanggan` di redaman dengan `id` di pelanggan
    }
}
