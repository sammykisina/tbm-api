<?php

declare(strict_types=1);

namespace App\Http\Resources\Authority;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorityBullingReportResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'type' => 'bully report',
            'attributes' => [
                'sender' => [
                    'name' => $this->data['sender']['name'],
                    'email' => $this->data['sender']['email'],
                ],
                'receiver' => [
                    'name' => $this->data['receiver']['name'],
                    'email' => $this->data['receiver']['email'],
                ],
                'receiverLocation' => [
                    'lat' => $this->data['senderLocation']['lat'],
                    'lon' => $this->data['senderLocation']['lon']
                ],
                'reporter' => [
                    'name' => $this->data['reporter']['name'],
                    'email' => $this->data['reporter']['email'],
                ],
                'message' => $this->data['message'],
                'created_at' =>  $this->created_at->format('M d, Y'),
                'status' => $this->read_at == null ? "unread" : "read",
            ]
        ];
    }
}
