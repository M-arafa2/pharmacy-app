<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class DoctorResource extends JsonResource
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
            'pharmacy' => $this->pharmacy->staff->name,
            'pharmacy_id' => $this->pharmacy_id,
        ];
    }
}
