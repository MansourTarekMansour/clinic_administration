<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'national_id' => $this->national_id,
            'city' => $this->city,
            'details' => $this->details,
            'phone1' => $this->phone1,
            'phone2' => $this->phone2,
            'disease_type' => $this->disease_type,
            'medical_rec' => $this->medical_rec,
            'visits' => $this->visits
        ];
    }
}
