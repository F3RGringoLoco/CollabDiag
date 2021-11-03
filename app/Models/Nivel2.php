<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel2 extends Model
{
    //use HasFactory;
    protected $table = 'nivel2s';
    public $incrementing = false;
    public $primaryKey = 'title_slug';
    protected $keyType = 'char';
}
