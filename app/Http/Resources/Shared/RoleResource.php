<?php

declare(strict_types=1);

namespace App\Http\Resources\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'type' => 'role',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'slug'  => $this->slug
            ]
        ];
    }
}
