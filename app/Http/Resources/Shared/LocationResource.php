<?php

declare(strict_types=1);

namespace App\Http\Resources\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'type' => 'location',
            'attributes' => [
                'uuid' => $this->uuid,
                'coordinates' => [
                    'lat' => $this->lat,
                    'lon' => $this->lon
                ],
            ]
        ];
    }
}
