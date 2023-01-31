<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Shared\NormalUserRequest;
use App\Notifications\Domains\Shared\TwoFactorCode;
use Domains\Shared\Actions\CreateUser;
use Domains\Shared\Models\Location;
use Domains\Shared\Models\Role;
use Illuminate\Support\Facades\Log;
use JustSteveKing\StatusCode\Http;

class RegisterController {
    public function __invoke(NormalUserRequest $request) {
        try {
            $default_user_role = Role::query()->where('slug', config(key: 'hydra.default_user_role_slug'))->first();
            $data = array_merge($request->validated(), [
                'role_id' => $default_user_role->id
            ]);
            $user = CreateUser::handle($data);

            Location::create([
                'lat' => $request->get(key: 'lat'),
                'lon' => $request->get(key: 'lon'),
                'user_id' => $user->id
            ]);

            // generate and send the two factor auth
            $user->generateTwoFactorCode();
            $user->notify(new TwoFactorCode);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => "Welcome to TBM. Check you email for verification code.",
                    'user' => [
                        'uuid' => $user->uuid
                    ],
                ],
                status: Http::OK()
            );
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Something went wrong.'
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }
    }
}
