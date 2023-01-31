<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string(column: 'two_factor_code')->nullable();
            $table->dateTime(column: 'two_factor_expires_at')->nullable();
            $table->string('password');

            $table->foreignId(column: 'role_id')
                   ->index()
                   ->constrained()
                   ->cascadeOnDelete();

            $table->rememberToken();
            $table->timestamps();
        });
    }
};
