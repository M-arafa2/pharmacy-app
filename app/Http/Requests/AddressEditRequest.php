<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'area_id' => 'required|integer',
            'street_name' => 'required|string',
            'building_number' => 'required|integer',
            'floor_number' => 'required|integer',
            'flat_number' => 'required|integer',
            'is_main' => 'required|integer',
        ];
    }
}
