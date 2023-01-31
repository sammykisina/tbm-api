<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('bullymarkers', function (Blueprint $table) {
            $table->id();

            $table->integer(column: 'sender_id')->unique();
            $table->integer(column: 'bully_count')->default(0);

            $table->timestamps();
        });
    }
};
