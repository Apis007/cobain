<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model{
    use SoftDeletes;
    
    protected $table = 'pelanggan';
    protected $primaryKey = 'id';
    // public $incrementing = false;
    
    protected $fillable = [
        'id'
    ];

    public function redaman()
{
    return $this->hasMany(Redaman::class, 'id');
}

public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'teknisi_id');
    }
}
