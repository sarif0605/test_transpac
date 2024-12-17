<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class WorkUnits extends Model
{

    protected $table = 'work_units';
    protected $primaryKey = "id";
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'unit_name', 'grade', 'echelon'
    ];
}
