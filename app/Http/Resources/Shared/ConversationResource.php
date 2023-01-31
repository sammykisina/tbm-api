<?php

declare(strict_types=1);

namespace App\Http\Resources\Shared;

use App\Http\Resources\Executive\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'type' => 'conversation',
            'attributes' => [
                'uuid' => $this->uuid,
            ],
            'relationships' => [
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
                'messages' => MessageResource::collection(
                    resource: $this->whenLoaded(
                        relationship: 'messages'
                    )
                )
            ]
        ];
    }
}
