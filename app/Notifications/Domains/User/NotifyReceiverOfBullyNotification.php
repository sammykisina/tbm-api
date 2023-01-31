<?php

declare(strict_types=1);

namespace App\Notifications\Domains\User;

use Domains\Shared\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifyReceiverOfBullyNotification extends Notification {
    use Queueable;

    public function __construct(
        public User $sender
    ) {
    }

    public function via($notifiable): array {
        return ['database'];
    }

    public function toArray($notifiable): array {
        return [
            'message' => 'Please be advised that the user '. $this->sender->name ." under email ". $this->sender->email .' has been flagged as a bully '. $this->sender->bully_flags . " time (s) before.",
            'type' => 'notify'
        ];

    }
}
