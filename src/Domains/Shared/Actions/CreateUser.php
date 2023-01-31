<?php

declare(strict_types=1);

namespace Domains\Shared\Actions;

use Domains\Shared\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser {
    public static function handle(array $user_data): User {
        return User::create([
            'email' => $user_data['email'],
            'password' => Hash::make($user_data['password']),
            'name' => $user_data['name'],
            'role_id' => $user_data['role_id']
        ]);
    }
}
