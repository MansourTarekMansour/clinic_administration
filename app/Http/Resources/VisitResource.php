<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'name' => $this->name,
            'treatment_plan' => $this->treatment_plan,
            'type' => $this->type,
            'units_number' => $this->units_number,
            'cost' => $this->cost,
            'discount' => $this->discount,
            'total' => $this->total,
            'paid' => $this->paid,
            'remaining' => $this->remaining,
            'note' => $this->note,
            'date' => $this->date,
        ];
    }
}
