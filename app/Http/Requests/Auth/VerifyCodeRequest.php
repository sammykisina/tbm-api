<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCodeRequest extends FormRequest {
    public function rules(): array {
        return [
            'code' => [
                'required',
                'size: 6',
            ],
        ];
    }
}
