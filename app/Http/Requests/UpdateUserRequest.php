<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateUserRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email',
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function passedValidation()
    {
        $hasValidInput = false;
        foreach ($this->rules() as $field => $rule) {
            if (!is_null($this->input($field))) {
                $hasValidInput = true;
                break;
            }
        }

        if (!$hasValidInput) {
            throw ValidationException::withMessages([
                'general' => 'At least one field must be provided.'
            ]);
        }
    }
}
