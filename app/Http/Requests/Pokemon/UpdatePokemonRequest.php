<?php

namespace App\Http\Requests\Pokemon;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePokemonRequest extends FormRequest
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
            'name' => 'string|max:50',
            'type' => 'string|max:100',
            'photo' => 'string|max:255',
        ];
    }
}
