<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'appointments';

    protected $fillable = [
        'medic_id',
        'patient_id',
        'date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relationship: An appointment belongs to a medic.
     */
    public function medic()
    {
        return $this->belongsTo(Medic::class);
    }

    /**
     * Relationship: An appointment belongs to a patient.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
