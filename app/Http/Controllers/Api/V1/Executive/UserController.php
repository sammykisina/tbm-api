<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Executive;

use App\Http\Requests\Shared\NormalUserRequest;
use App\Http\Resources\Executive\UserResource;
use App\Notifications\Domains\User\BullyFlagNotification;
use Domains\Shared\Actions\CreateUser;
use Domains\Shared\Models\Block;
use Domains\Shared\Models\Location;
use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class UserController {
    public function index(): JsonResponse {
        $users = QueryBuilder::for(
            subject: User::class
        )->allowedIncludes(
            includes: ['role', 'location', 'blocks',"blocked"]
        )->defaultSort('-created_at')->get();

        return response()->json(
            data: UserResource::collection(
                resource: $users
            ),
            status: Http::OK()
        );
    }

    public function store(NormalUserRequest $request): JsonResponse {
        try {
            $user = CreateUser::handle([
                'email' => $request->get(key: 'email'),
                'name' => $request->get(key: 'name'),
                'password' => $request->get(key: 'password'),
                'role_id' => Role::query()->where('slug', config(key: 'hydra.default_user_role_slug'))->first()->id
            ]);

            Location::create([
                'user_id' => $user->id
            ]);

            return response()->json(
                data : [
                    'error' => 0,
                    'message' => "User created successfully."
                ],
                status: Http::CREATED()
            );
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(
                data : [
                    'error' => 1,
                    'message' => "Something went wrong."
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }
    }

    public function update(Request $request, User $user): JsonResponse {
        try {
            $validated = $request->validate([
                'name'=> [
                    'required',
                    'string'
                ],
                'email' => [
                    'required',
                    'required', 'email', Rule::unique('users')->ignore($user->id),
                ],
                'password' => [
                    'required',
                    'string'
                ],
            ]);


            $user->update($validated);

            return response()->json(
                data : [
                    'error' => 0,
                    'message' => "User updated successfully."
                ],
                status: Http::ACCEPTED()
            );
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(
                data : [
                    'error' => 1,
                    'message' => "Something went wrong."
                ],
                status: Http::NOT_MODIFIED()
            );
        }
    }

    public function destroy(User $user): JsonResponse {
        try {
            $user->delete();
            return response()->json(
                data : [
                    'error' => 0,
                    'message' => "User deleted successfully."
                ],
                status: Http::ACCEPTED()
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

    public function block(User $blockingUser, User $userToBeBlocked): JsonResponse {
        try {
            $userBlocked = Block::query()->where('blocker_id', $blockingUser->id)->first();

            if ($userBlocked) {
                // unblock
                $userBlocked->delete();
                return response()->json(
                    data : [
                        'error' => 0,
                        'message' => "User unblocked successfully."
                    ],
                    status: Http::ACCEPTED()
                );
            } else {
                // block
                Block::create([
                    'blocker_id' => $blockingUser->id,
                    'blocked_id' => $userToBeBlocked->id
                ]);

                return response()->json(
                    data: [
                        'error' => 0,
                        'message' => 'User blocked successfully.'
                    ],
                    status: Http::ACCEPTED()
                );
            }
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

    public function info(User $user): JsonResponse {
        $user = User::query()
        ->with(['blocks',"blocked"])
        ->where('id', $user->id)
        ->first();

        return response()->json(
            data: new UserResource(
                resource: $user
            ),
            status: Http::OK()
        );
    }

    public function sendWarning(User $authority, User $user): JsonResponse {
        try {
            $user->notify(new BullyFlagNotification(authority: $authority));
            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Warning send successfully.'
                ],
                status: Http::ACCEPTED()
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
