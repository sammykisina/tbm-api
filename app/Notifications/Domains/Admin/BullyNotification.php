<?php

declare(strict_types=1);

namespace App\Notifications\Domains\Admin;

use Domains\Shared\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BullyNotification extends Notification {
    use Queueable;

    public function __construct(
        public User $sender,
        public User $receiver,
        public string $message,
    ) {
    }

    public function via($notifiable) {
        return ['database'];
    }

    public function toArray($notifiable) {
        return [
            'sender' => [
                'email' => $this->sender->email,
                'name' => $this->sender->name,
            ],
            'receiver' => [
                'email' => $this->receiver->email,
                'name' => $this->receiver->name,
            ],
            'message' => $this->message
        ];
    }
}
