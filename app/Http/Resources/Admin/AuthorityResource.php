<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use App\Http\Resources\Shared\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorityResource extends JsonResource {
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
                )
            ]
        ];
    }
}
