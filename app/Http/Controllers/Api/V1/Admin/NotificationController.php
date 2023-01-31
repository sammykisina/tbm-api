<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Resources\Admin\NotificationResource;
use App\Http\Resources\Authority\AuthorityBullingReportResource;
use Domains\Shared\Models\User;
use JustSteveKing\StatusCode\Http;

class NotificationController {
    public function getNotifications(User $member) {
        $notifications = $member->notifications;

        return response()->json(
            data: NotificationResource::collection(
                resource: $notifications
            ),
            status: Http::OK()
        );
    }

    public function getReports(User $authority) {
        $notifications = $authority->notifications;

        return response()->json(
            data: AuthorityBullingReportResource::collection(
                resource: $notifications
            ),
            status: Http::OK()
        );
    }
}
