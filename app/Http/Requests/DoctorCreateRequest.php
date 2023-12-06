<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorCreateRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',

            'pharmacy_id' => 'required|integer'

        ];
        if($this->isMethod('POST')) {
            //$rules['password'] = 'required|min:8';
            //$rules['image'] = 'required|image|mimes:jpg,png,jpeg,gif,svg|max:4048';
            $rules += [
                'password' => 'required|min:8',
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:4048',
                'email' => 'required|email|unique:staff,email',
                'national_id' => 'required|integer|unique:staff,national_id',
            ];
        } elseif($this->isMethod('PUT')) {
            $rules += [
                'password' => 'sometimes|min:8',
                'image' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:4048',
                'email' => 'required|email',
                'national_id' => 'required|integer',
            ];
        }

        return $rules;
    }
}
