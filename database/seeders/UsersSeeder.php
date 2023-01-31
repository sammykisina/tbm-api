<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domains\Shared\Models\Location;
use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder {
    public function run(): void {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $role = Role::where('slug', 'admin')->first();
        $admin = User::create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'name' => 'Admin',
            'role_id' => $role->id
        ]);
        Location::create(['user_id' => $admin->id]);

        $authority_role = Role::where('slug', 'authority')->first();
        $authority = User::create([
            'email' => 'authority@authority.com',
            'password' => Hash::make('authority'),
            'name' => 'Authority',
            'role_id' => $authority_role->id
        ]);
        Location::create(['user_id' => $authority->id]);

        $user_role = Role::where('slug', 'user')->first();
        $user = User::create([
            'email' => 'user@ig.com',
            'password' => Hash::make('user'),
            'name' => 'User',
            'role_id' => $user_role->id
        ]);
        Location::create(['user_id' => $user->id]);
    }
}
