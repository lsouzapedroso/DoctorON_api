<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medic extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medics';

    protected $fillable = [
        'name',
        'specialization',
        'city_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'medic_id');
    }

    public function patients()
    {
        return $this->hasManyThrough(
            Patient::class,
            Appointment::class,
            'medic_id',
            'id',
            'id',
            'patient_id'
        );
    }
}
