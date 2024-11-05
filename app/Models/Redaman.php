<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Redaman extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    protected $table = 'redaman';
    protected $primaryKey = 'id';

    protected $fillable = [
        'port',
        'redaman',
        'id_pelanggan', // Kolom ini akan digunakan sebagai referensi ke `id` di `pelanggan`
        'nama',
        'alamat',
        'paket',
        'created_at',
    ];

    // Relasi ke model Pelanggan tanpa foreign key constraint
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id');
    }
}
