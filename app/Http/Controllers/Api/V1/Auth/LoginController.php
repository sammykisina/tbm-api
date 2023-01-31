<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Notifications\Domains\Shared\TwoFactorCode;
use Domains\Shared\Models\Location;
use Domains\Shared\Models\User;
use Illuminate\Support\Facades\Hash;
use JustSteveKing\StatusCode\Http;

class LoginController {
    public function __invoke(LoginRequest $request) {
        $user = User::query()->where('email', $request->get('email'))->first();

        if (!$user || ! Hash::check(value: $request->get(key: 'password'), hashedValue: $user->password)) {
            return response()->json(
                data: [
                    'error' => 1,
                    "message" => "Invalid credentials.Please enter the correct email and Password."
                ],
                status: Http::NOT_FOUND()
            );
        }

        if (config(key: 'hydra.delete_previous_access_tokens_on_login')) {
            $user->tokens()->delete();
        }


        $location = Location::query()->where('user_id', $user->id)->first();
        $location->update([
            'lat' => $request->get(key: 'lat'),
            'lon' => $request->get(key: 'lon'),
        ]);



        // generate and send the two factor auth
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode);

        return response()->json(
            data: [
                'error' => 0,
                'message' => 'Welcome. Check you email for verification code.',
                'user' => [
                    'uuid' => $user->uuid,
                ],
            ]
        );

        // $role = $user->role()->pluck('slug')->all();
        // $plain_text_token = $user->createToken('tbm-api-token', $role)->plainTextToken;

        // update location


        // return response()->json(
        //     data: [
        //         'error' => 0,
        //         'message' => "Verified. Have fun.",
        //         'user' => [
        //             'uuid' => $user->uuid,
        //             'name' => $user->name,
        //             'email' => $user->email,
        //             'id' => $user->id,
        //             'role' => $role[0],
        //         ],
        //         'token' => $plain_text_token
        //     ],
        //     status: Http::ACCEPTED()
        // );
    }
}
