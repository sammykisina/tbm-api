<?php

declare(strict_types=1);

namespace App\Http\Resources\Executive;

use Illuminate\Http\Resources\Json\JsonResource;

class BlockedResource extends JsonResource {
    public function toArray($request) {
        return $this->blocker_id;
    }
}
