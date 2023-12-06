<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PharmacyCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:4048',
            'password' => 'required',
            'national_id' => 'required|integer|unique:staff,national_id',
            'area_id' => 'required',
            'priority' => 'required|integer',

        ];
    }
}
