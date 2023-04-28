<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function medicalProcedures()
    {
        return $this->hasMany(MedicalProcedure::class);
    }
    public function types()
    {
        return $this->hasManyThrough(Type::class, MedicalProcedure::class);
    }
}
