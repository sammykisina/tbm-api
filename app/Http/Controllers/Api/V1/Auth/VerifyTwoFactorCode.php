<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Auth\VerifyCodeRequest;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

class VerifyTwoFactorCode {
    public function __invoke(VerifyCodeRequest $request, User $user): JsonResponse {
        if ($user->two_factor_code) {
            if ($user->two_factor_expires_at->lt(now())) {
                $user->resetTwoFactorCode();
                $user->tokens()->delete();

                return response()->json(
                    data: [
                        'error' => 1,
                        'message' => "Code expired.Login to get a new one."
                    ],
                    status: Http::NOT_IMPLEMENTED()
                );
            } else {
                if ($request->get(key: 'code') === $user->two_factor_code) {
                    $role = $user->role()->pluck('slug')->all();
                    $plain_text_token = $user->createToken('tbm-api-token', $role)->plainTextToken;

                    $user->resetTwoFactorCode();
                    return response()->json(
                        data: [
                            'error' => 0,
                            'message' => "Verified. Have fun.",
                            'user' => [
                                'uuid' => $user->uuid,
                                'name' => $user->name,
                                'email' => $user->email,
                                'role' => $role[0],
                                'id' => $user->id
                            ],
                            'token' => $plain_text_token
                        ],
                        status: Http::ACCEPTED()
                    );
                } else {
                    return response()->json(
                        data: [
                            'error' => 1,
                            'message' => "The code you provided is wrong.Try again."
                        ],
                        status: Http::NOT_IMPLEMENTED()
                    );
                }
            }
        } else {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => "Try logging in again to get the code."
                ],
                status: Http::NOT_ACCEPTABLE()
            );
        }
    }
}
