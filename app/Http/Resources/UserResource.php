<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => url(Storage::url($this->image)),
            'phone' => $this->phone,
            'admin' => $this->is_admin ? 'admin' : 'user',
        ];
    }
}
