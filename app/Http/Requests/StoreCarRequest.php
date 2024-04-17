<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            'brand' => ['required','string','max:255'],
            'model' => ['required','string','max:255'],
            'police_number' => ['required','string', 'min:3', 'max:12'],
            'rent_fee' => ['required', 'numeric'],
            'image' => ['nullable','file','max:5124','mimes:jpeg,png,jpg'],
        ];
    }
}
