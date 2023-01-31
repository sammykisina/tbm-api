<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Notifications\Domains\Authority\NotifyOfBullying;
use Domains\Shared\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JustSteveKing\StatusCode\Http;

class ReportBullyingController {
    public function __invoke(Request $request, User $admin) {
        try {
            $validated = $request->validate([
                'senderEmail'=> [
                    'required',
                    'email',
                    'exists:users,email'
                ],
                'receiverEmail' => [
                    'required',
                    'email',
                    'exists:users,email'
                ],
                'authorityEmail' => [
                    'required',
                    'email',
                    'exists:users,email'
                ],
                'notificationId' => [
                    'required',
                    'string',
                    'exists:notifications,id'
                ],
                "message" => [
                    'required',
                    'string'
                ]
            ]);

            // find the users
            $sender = User::query()->where('email', $validated['senderEmail'])->first();
            $receiver = User::query()->where('email', $validated['receiverEmail'])->first();
            $authority = User::query()->where('email', $validated['authorityEmail'])->first();
            $senderLocation = $sender->location;

            $authority->notify(
                new NotifyOfBullying(
                    sender: $sender,
                    receiver: $receiver,
                    reporter: $admin,
                    message:  $validated['message'],
                    senderLocation: $senderLocation
                )
            );

            $notification = $admin->unreadNotifications
                ->where('id', $validated['notificationId'])
                ->first();
            $notification->delete();

            return response()->json(
                data: [
                    'error' => 0,
                    'data' => $authority->notifications,
                    'message' => 'Report send successfully.'
                ],
                status: Http::ACCEPTED()
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
}
