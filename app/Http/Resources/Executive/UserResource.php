<?php

declare(strict_types=1);

namespace App\Http\Resources\Executive;

use App\Http\Resources\Shared\LocationResource;
use App\Http\Resources\Shared\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'type' => 'user',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'email' => $this->email,
            ],
            'relationships' => [
                'role' => new RoleResource(
                    resource: $this->whenLoaded(
                        relationship: 'role'
                    )
                ),
                'location' => new LocationResource(
                    resource: $this->whenLoaded(
                        relationship: 'location'
                    )
                ),
                'blocks' =>  BlocksResource::collection(
                    resource: $this->whenLoaded(
                        relationship: 'blocks'
                    )
                ),
                'blocked' =>  BlockedResource::collection(
                    resource: $this->whenLoaded(
                        relationship: 'blocked'
                    )
                )
            ]
        ];
    }
}
