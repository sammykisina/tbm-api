<?php

declare(strict_types=1);

namespace App\Http\Requests\Shared;

use Illuminate\Foundation\Http\FormRequest;

class NormalUserRequest extends FormRequest {
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
