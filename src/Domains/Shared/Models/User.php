<?php

declare(strict_types=1);

namespace Domains\Shared\Models;

use Database\Factories\UserFactory;
use Domains\Shared\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasUuid;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;


    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'role_id',
        'bully_flags',
        'two_factor_code',
        'two_factor_expires_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
        'two_factor_expires_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_expires_at' => 'datetime',
    ];

    public function role(): BelongsTo {
        return $this->belongsTo(
            related: Role::class,
            foreignKey: 'role_id'
        );
    }

    public function generateTwoFactorCode(): void {
        $this->timestamps = false;

        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(value: 10);
        $this->save();
    }

    public function resetTwoFactorCode() {
        $this->timestamps = false; //Dont update the 'updated_at' field yet

        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function location(): HasOne {
        return $this->hasOne(
            related: Location::class,
            foreignKey: 'user_id'
        );
    }

    public function blocks(): HasMany {
        return $this->hasMany(
            related: Block::class,
            foreignKey: "blocker_id"
        );
    }

    public function blocked(): HasMany {
        return $this->hasMany(
            related: Block::class,
            foreignKey: "blocked_id"
        );
    }

    protected static function newFactory(): UserFactory {
        return new UserFactory;
    }
}
