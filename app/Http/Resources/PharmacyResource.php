<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PharmacyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'image' => $this->staff->image,
            'name' => $this->staff->name,
            'email' => $this->staff->email,
            'national_id' => $this->staff->national_id,
            'area' => $this->area_id,
            'priority' => $this->priority,
        ];
    }
}
