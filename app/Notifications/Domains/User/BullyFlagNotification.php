<?php

declare(strict_types=1);

namespace App\Notifications\Domains\User;

use Domains\Shared\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BullyFlagNotification extends Notification {
    use Queueable;

    public function __construct(
        public User $authority
    ) {
    }

    public function via($notifiable): array {
        return ['database'];
    }

    public function toArray($notifiable): array {
        return [
            'flag_count' => $notifiable->bully_flags,
            'message' => "You have been warned by the authority in accordance to your resent 
                behaver towards another user of our platform.Further warning will lead to automatic blocking and further investigations from us.",
            'authority' => [
                'name' => $this->authority->name,
                'email' => $this->authority->email
            ],
            'type' => 'warning'
        ];
    }
}
