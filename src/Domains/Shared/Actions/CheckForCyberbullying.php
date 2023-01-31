<?php

declare(strict_types=1);

namespace Domains\Shared\Actions;

use App\Notifications\Domains\Admin\BullyNotification;
use Domains\Admin\Models\Bullymarker;
use Domains\Shared\Models\User;

class CheckForCyberBullying {
    public static function handle(string $message, int $senderId, int $receiverId) {
        $bullyingWords = ['asshole', 'fuck', 'motherfucker'];
        $sender = User::query()
            ->where('id', $senderId)
            ->first();
        $receiver = User::query()
            ->where('id', $receiverId)
            ->first();
        $admin = User::query()
        ->where('email', 'admin@admin.com')
        ->first();

        // initialize
        if (Bullymarker::where('sender_id', $senderId)->exists()) {
        } else {
            Bullymarker::create([
                "sender_id" => $senderId,
                "bully_count" => 0
            ]);
        }

        foreach ($bullyingWords  as $bullyingWord) {
            if (strpos($message, $bullyingWord) !== false) {
                $checkForBullyCount = Bullymarker::query()->where('sender_id', $senderId)->first();
                if ($checkForBullyCount->bully_count >= 2) {
                    //notify the admin
                    $admin->notify(new BullyNotification($sender, $receiver, $message));
                    $checkForBullyCount->update([
                        'bully_count' => 0
                    ]);
                } else {
                    $checkForBullyCount->update([
                        'bully_count' => $checkForBullyCount->bully_count + 1
                    ]);
                }
            }
        }
    }
}
