<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentPlanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'medical_procedures' => MedicalProcedureResource::collection($this->whenLoaded('medicalProcedures')),
        ];
    }
}
