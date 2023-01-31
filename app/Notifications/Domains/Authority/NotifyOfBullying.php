<?php

declare(strict_types=1);

namespace App\Notifications\Domains\Authority;

use Domains\Shared\Models\Location;
use Domains\Shared\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifyOfBullying extends Notification {
    use Queueable;

    public function __construct(
        public User $sender,
        public User $receiver,
        public User $reporter,
        public string $message,
        public Location $senderLocation
    ) {
    }

    public function via($notifiable) {
        return ['database'];
    }

    public function toArray($notifiable): array {
        return [
            'sender' => [
                'email' => $this->sender->email,
                'name' => $this->sender->name,
            ],
            'receiver' => [
                'email' => $this->receiver->email,
                'name' => $this->receiver->name,
            ],
            'message' => $this->message,
            'reporter' => [
                'email' => $this->reporter->email,
                'name' => $this->reporter->name
            ],
            'senderLocation' => [
                'lat' => $this->senderLocation->lat,
                'lon' => $this->senderLocation->lon,
            ]
        ];
    }
}
