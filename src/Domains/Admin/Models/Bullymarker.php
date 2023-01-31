<?php

declare(strict_types=1);

namespace Domains\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Bullymarker extends Model {
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'bully_count'
    ];
}
