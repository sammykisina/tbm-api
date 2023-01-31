<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid(column: 'id')->primary();
            $table->string(column: 'type');
            $table->morphs(name: 'notifiable');
            $table->text(column: 'data');
            $table->timestamp(column: 'read_at')->nullable();
            $table->timestamps();
        });
    }
};
