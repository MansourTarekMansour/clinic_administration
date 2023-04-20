<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientVisit extends Model
{
    protected $fillable = [
        'name',
        'treatment_plan',
        'type',
        'units_number',
        'cost',
        'discount',
        'total',
        'paid',
        'remaining',
        'note',
        'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}