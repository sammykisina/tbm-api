<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: 'lat')->nullable();
            $table->string(column: 'lon')->nullable();

            $table->foreignId(column: 'user_id')->index()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
};
