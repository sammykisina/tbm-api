<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserNotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'user notification',
            'attributes' => [
                'message' => $this->data['message'],
                'flag_count' => $this->data['flag_count'],
                'authority' => [
                    'name' => $this->data['authority']['name'],
                    'email' => $this->data['authority']['email'],
                ],
                'created_at' =>  $this->created_at->format('M d, Y'),
            ]
        ];
    }
}
