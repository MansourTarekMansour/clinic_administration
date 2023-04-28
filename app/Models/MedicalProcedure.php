<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalProcedure extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    

    public function types()
    {
        return $this->hasMany(Type::class);
    }

    public function treatmentPlan()
    {
        return $this->belongsTo(TreatmentPlan::class);
    }
}
