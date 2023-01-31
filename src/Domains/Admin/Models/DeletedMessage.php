<?php

declare(strict_types=1);

namespace Domains\Admin\Models;

use Domains\Shared\Concerns\HasUuid;
use Domains\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeletedMessage extends Model {
    use HasUuid;
    use HasFactory;


    public $fillable = [
        'uuid',
        'message',
        'time_send',
        'location_lat',
        'location_log',
        'sender_id',
        'receiver_id'
    ];


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
}
