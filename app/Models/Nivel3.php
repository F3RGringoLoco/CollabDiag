<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel3 extends Model
{
    //use HasFactory;
    protected $table = 'nivel3s';
    public $incrementing = false;
    public $primaryKey = 'title_slug';
    protected $keyType = 'char';
}
