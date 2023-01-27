<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'between:5,255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->id)],
            'password' => [Rule::requiredIf(fn() => !empty($this->id)), 'string', 'between:6,255']
        ];
    }
}
