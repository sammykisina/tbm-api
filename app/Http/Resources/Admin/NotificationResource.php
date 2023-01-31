<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'type' => 'bully notification',
            'attributes' => [
                'sender' => [
                    'name' => $this->data['sender']['name'],
                    'email' => $this->data['sender']['email'],
                ],
                'receiver' => [
                    'name' => $this->data['receiver']['name'],
                    'email' => $this->data['receiver']['email'],
                ],
                'message' => $this->data['message'],
                'created_at' =>  $this->created_at->format('M d, Y'),
                'status' => $this->read_at == null ? "unread" : "read"
            ]
        ];
    }
}
