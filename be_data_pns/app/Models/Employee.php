<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = "nip";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'nip',
        'name',
        'position',
        'duty_place',
        'religion',
        'phone_number',
        'npwp',
        'email',
        'photo',
        'work_unit_id'
    ];

    public function work_unit()
    {
        return $this->belongsTo(WorkUnits::class, 'work_unit_id');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'employee_nip', 'nip');
    }

    // public function sluggable(): array
    // {
    //     return [
    //         'slug' => [
    //             'source' => 'name'
    //         ]
    //     ];
    // }
}
