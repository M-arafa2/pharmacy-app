<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'image' => $this->image,
            'name' => $this->name,
            'email' => $this->email,
            'national_id' => $this->national_id,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'mobile_number' => $this->mobile_number,
        ];
    }
}
