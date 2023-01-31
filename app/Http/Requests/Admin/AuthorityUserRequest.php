<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AuthorityUserRequest extends FormRequest {
    public function rules(): array {
        return [
            'name' => [
                'required',
                'string'

            ],
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string'
            ],
        ];
    }
}
