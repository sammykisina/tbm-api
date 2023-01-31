<?php

declare(strict_types=1);

namespace App\Http\Resources\Shared;

use App\Http\Resources\Executive\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'type' => 'message',
            'attributes' => [
                'uuid' => $this->uuid,
                'body' => $this->body ?? $this->message,
                'read' => $this->read,
                'type' => $this->type,
                'time_send' => $this->time_send,
                'created_at' => $this->created_at->shortAbsoluteDiffForHumans(),
                'location' => "location",
            ],
            "relationships" => [
                'sender' => new UserResource(
                    resource: $this->whenLoaded(
                        relationship: 'sender'
                    )
                ),
                'receiver' => new UserResource(
                    resource: $this->whenLoaded(
                        relationship: 'receiver'
                    )
                ),
            ]
        ];
    }
}
