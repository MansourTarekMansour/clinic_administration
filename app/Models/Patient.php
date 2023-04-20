<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'name',
        'national_id',
        'city',
        'details',
        'phone1',
        'phone2',
        'disease_type',
        'medical_rec',
    ];

    public function visits()
    {
        return $this->hasMany(PatientVisit::class);
    }
}
