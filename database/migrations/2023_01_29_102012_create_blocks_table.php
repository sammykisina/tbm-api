<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->unsignedBigInteger('blocker_id');
            $table->foreign('blocker_id')->references('id')->on('users');

            $table->unsignedBigInteger('blocked_id');
            $table->foreign('blocked_id')->references('id')->on('users');
            $table->timestamps();
        });
    }
};
