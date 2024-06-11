<?php

namespace App\Http\Requests\APi\V1;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
        $roles = 'editor,moderator,supervisor';
        return [
            'user_id' => ['required', 'exists:users,id', 'unique:admins,user_id'],
            'role' => ['required', 'in:' . $roles],
        ];
    }
}
