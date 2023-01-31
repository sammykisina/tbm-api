<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Requests\Admin\AuthorityUserRequest;
use App\Http\Resources\Admin\AuthorityResource;
use Domains\Shared\Actions\CreateUser;
use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class AuthorityUserController {
    public function index(): JsonResponse {
        $users = QueryBuilder::for(
            subject: User::class
        )->allowedIncludes(
            includes: ['role']
        )->defaultSort('-created_at')->get();

        return response()->json(
            data: AuthorityResource::collection(
                resource: $users
            ),
            status: Http::OK()
        );
    }

    public function store(AuthorityUserRequest $request): JsonResponse {
        try {
            CreateUser::handle([
                'email' => $request->get(key: 'email'),
                'name' => $request->get(key: 'name'),
                'password' => Hash::make($request->get(key: 'password')),
                'role_id' => Role::query()->where('slug', "authority")->first()->id
            ]);

            return response()->json(
                data : [
                    'error' => 0,
                    'message' => "Authority User created successfully."
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

    public function update(Request $request, User $authority): JsonResponse {
        try {
            $validated = $request->validate([
                'name'=> [
                    'required',
                    'string'
                ],
                'email' => [
                    'required',
                    'required', 'email', Rule::unique('users')->ignore($authority->id),
                ],
                'password' => [
                    'required',
                    'string'
                ],
            ]);


            $authority->update($validated);

            return response()->json(
                data : [
                    'error' => 0,
                    'message' => "Authority User updated successfully."
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

    public function destroy(User $authority): JsonResponse {
        try {
            $authority->delete();
            return response()->json(
                data : [
                    'error' => 0,
                    'message' => "Authority deleted successfully."
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
