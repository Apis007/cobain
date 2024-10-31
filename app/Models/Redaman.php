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
    // public $incrementing = false;
    protected $fillable = [
        'port',
        'redaman',
        'id_pelanggan',
        'nama',
        'alamat',
        'paket',
    ];

    // Tambahkan ini untuk melihat query yang dijalankan
    // protected static function boot()
    // {
    //     parent::boot();
        
    //     static::creating(function ($model) {
    //         \Log::info("Creating new redaman:", $model->toArray());
    //     });
    // }

    function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }
}
