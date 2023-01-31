<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Shared;

use App\Http\Resources\Shared\ConversationResource;
use App\Http\Resources\Shared\MessageResource;
use Domains\Admin\Models\DeletedMessage;
use Domains\Shared\Actions\CheckForCyberBullying;
use Domains\Shared\Models\Conversation;
use Domains\Shared\Models\Message;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JustSteveKing\StatusCode\Http;

class ChatController {
    public function getAllConversations(User $user): JsonResponse {
        $conversations = Conversation::query()
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('last_time_message', 'DESC')
            ->with(['sender', 'receiver', 'messages.receiver','messages.sender'])
            ->get();

        return response()->json(
            data: ConversationResource::collection(
                resource: $conversations
            ),
            status: Http::OK()
        );
    }

    public function checkIfConversationExists(Request $request): JsonResponse {
        try {
            $validated = $request->validate([
                'receiver_id'=> [
                    'exists:users,id'
                ],
                'sender_id'=> [
                    'exists:users,id'
                ]
            ]);

            $checkedConversation = Conversation::query()
                ->where('receiver_id', $validated['sender_id'])
                ->where('sender_id', $validated['receiver_id'])
                ->orWhere('receiver_id', $validated['receiver_id'])
                ->where('sender_id', $validated['sender_id'])->get();

            if (count($checkedConversation) === 0) {
                Conversation::create([
                    'receiver_id' => $validated['receiver_id'],
                    'sender_id' =>  $validated['sender_id'],
                    'last_time_message' => now() // TODO: make a default value for this
                ]);

                return response()->json(
                    data: [
                        'error' => 0,
                        'message' => 'Conversation created.',
                    ],
                    status: Http::CREATED()
                );
            } elseif (count($checkedConversation) > 0) {
                return response()->json(
                    data: [
                        'error' => 0,
                        'message' => 'Conversation is among your chatlist. Click it to chat.',
                    ],
                    status: Http::CREATED()
                );
            }
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Something went wrong.'
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }
    }

    public function createMessage(Request $request, Conversation $conversation) {
        try {
            $conversation_id = $conversation->id;

            $validated = $request->validate([
                'receiver_id'=> [
                    'integer',
                    'exists:users,id'
                ],
                'sender_id'=> [
                    'integer',
                    'exists:users,id'
                ],
                'body' => [
                    'string',
                    'required'
                ],
            ]);

            $createdMessage = Message::create([
                'sender_id' => $validated['sender_id'],
                'receiver_id' => $validated['receiver_id'],
                'conversation_id' => $conversation->id,
                'body' => $validated['body']
            ]);

            $conversation->last_time_message = $createdMessage->created_at;
            $conversation->save();

            $updatedConversation = Conversation::query()
                ->where('id', $conversation_id)
                ->orderBy('last_time_message', 'DESC')
                ->with(['sender', 'receiver', 'messages.receiver','messages.sender'])
                ->first();

            CheckForCyberBullying::handle(
                message: $validated['body'],
                senderId: $validated['sender_id'],
                receiverId: $validated['receiver_id']
            );

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'message created.',
                    'conversation' => new ConversationResource(
                        resource:$updatedConversation
                    )
                ],
                status: Http::CREATED()
            );
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Something went wrong.'
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }
    }

    public function deleteMessage(Conversation $conversation, Message $message): JsonResponse {
        try {
            $message->delete();

            $updatedConversation = Conversation::query()
                ->where('id', $conversation->id)
                ->orderBy('last_time_message', 'DESC')
                ->with(['sender', 'receiver', 'messages.receiver','messages.sender'])
                ->first();

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'message created.',
                    'conversation' => new ConversationResource(
                        resource:$updatedConversation
                    )
                ],
                status: Http::CREATED()
            );

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Message deleted.'
                ],
                status: Http::ACCEPTED()
            );
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Something went wrong'
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }
    }

    public function getDeletedMessages(): JsonResponse {
        try {
            $messages = Message::withTrashed()->where('deleted_at', "!=", null)->get();

            if ($messages->count() > 0) {
                foreach ($messages as $message) {
                    DeletedMessage::create([
                        'message' => $message->body,
                        'time_send' => $message->created_at,
                        // add location details
                        'sender_id' => $message->sender_id,
                        'receiver_id' => $message->receiver_id
                    ]);

                    $message->forceDelete();
                }
            }

            $deleted_messages =  DeletedMessage::query()
                ->with(['sender', 'receiver'])
                ->get();

            return response()->json(
                data: MessageResource::collection(
                    resource: $deleted_messages
                ),
                status: Http::OK()
            );
        } catch (\Throwable $th) {
            Log::info($th);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'table not updated.'
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }
    }
}
