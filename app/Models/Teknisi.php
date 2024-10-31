<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Teknisi extends Model
{
    use SoftDeletes;
    
    protected $table = 'teknisi';
    protected $primaryKey = 'id';
}