<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
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
        dump($this->request);
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'image' => 'sometimes|image|mimes:jpg,png,jpeg,gif,svg|max:4048',
            'password' => 'sometimes',
            'national_id' => 'required|integer',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'mobile_number' => 'required|integer',
        ];
    }
}
