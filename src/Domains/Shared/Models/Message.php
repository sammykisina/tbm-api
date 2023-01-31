<?php

declare(strict_types=1);

namespace Domains\Shared\Models;

use Domains\Shared\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model {
    use HasUuid;
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'uuid',
        'sender_id',
        'receiver_id',
        'conversation_id',
        'read',
        'type',
        'body'
    ];

    public function conversation(): BelongsTo {
        return $this->belongsTo(
            related: Conversation::class,
            foreignKey: 'conversation_id'
        );
    }

    public function sender(): BelongsTo {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'sender_id'
        );
    }

    public function receiver(): BelongsTo {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'receiver_id'
        );
    }

    public function user(): BelongsTo {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'sender_id'
        );
    }
}
