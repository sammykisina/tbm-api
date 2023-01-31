<?php

declare(strict_types=1);

namespace Domains\Shared\Models;

use Domains\Shared\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Block extends Model {
    use HasUuid;
    use HasFactory;

    protected $fillable = [
        'uuid',
        'blocker_id',
        'blocked_id'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'blocker_id'
        );
    }
}
