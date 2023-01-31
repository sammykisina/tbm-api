<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Shared;

use App\Http\Resources\Admin\AdminNotificationResource;
use App\Http\Resources\Authority\AuthorityBullingReportResource;
use App\Http\Resources\User\UserNotificationResource;
use Domains\Shared\Models\User;
use JustSteveKing\StatusCode\Http;

class NotificationController {
    public function getAdminNotifications(User $admin) {
        $notifications = $admin->notifications;

        return response()->json(
            data: AdminNotificationResource::collection(
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

    public function getUserNotifications(User $user) {
        $notifications = $user->notifications;

        return response()->json(
            data: UserNotificationResource::collection(
                resource: $notifications
            ),
            status: Http::OK()
        );
    }
}
