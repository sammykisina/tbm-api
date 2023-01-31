<?php

declare(strict_types=1);

namespace Domains\Shared\Models;

use Domains\Shared\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Location extends Model {
    use HasUuid;
    use HasFactory;

    protected $fillable = [
        'uuid',
        'lat',
        'lon',
        'user_id'
    ];
}
