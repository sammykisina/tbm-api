<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Admin\AuthorityUserController;
use App\Http\Controllers\Api\V1\Admin\ReportBullyingController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\VerifyTwoFactorCode;
use App\Http\Controllers\Api\V1\Executive\UserController;
use App\Http\Controllers\Api\V1\Shared\ChatController;
use App\Http\Controllers\Api\V1\Shared\NotificationController;
use Illuminate\Support\Facades\Route;

/**
 * auth routes
 */
Route::prefix('auth')->as('auth:')->group(function () {
    Route::post('register', RegisterController::class)->name('register');
    Route::post('login', LoginController::class)->name('login');
    Route::post('verify/{user}', VerifyTwoFactorCode::class)->name('verify');
});

/**
 * routes to be accessed by admin
 */

Route::group([
    'prefix' => 'admin',
    'as' => 'admin:',
    'middleware' => ['auth:sanctum', 'abilities:admin']
], function () {
    Route::apiResource('users', UserController::class)
        ->except(['edit', 'create', "show"]);
    Route::apiResource('authority', AuthorityUserController::class)
        ->except(['edit', 'create', "show"]);

    Route::get('/messages', [ChatController::class, 'getDeletedMessages']);
    Route::post('/report/{admin}', ReportBullyingController::class);
    Route::get('/notifications/{admin}', [NotificationController::class, 'getAdminNotifications']);
});

Route::group([
    'prefix' => 'authority',
    'as' => 'authority:',
    'middleware' => ['auth:sanctum', 'abilities:authority']
], function () {
    Route::apiResource('users', UserController::class)
        ->except(['edit', 'create', 'store', 'update', 'destroy', 'show']);

    Route::get('/reports/{authority}', [NotificationController::class, 'getReports']);
    Route::post('/warnings/{authority}/users/{user}', [UserController::class, 'sendWarning']);
});

Route::group([
    'prefix' => 'members',
    'as' => 'members',
    'middleware' => ['auth:sanctum']
], function () {
    Route::apiResource('users', UserController::class)
        ->except(['edit', 'create', 'store', 'update', 'destroy', 'show']);

    Route::post('/conversations/exists', [ChatController::class, 'checkIfConversationExists']);
    Route::get('/conversations/{user}', [ChatController::class, 'getAllConversations']);
    Route::post('/conversations/{conversation}', [ChatController::class, 'createMessage']);
    Route::delete('/conversations/{conversation}/messages/{message}', [ChatController::class, 'deleteMessage']);

    Route::get('/{blockingUser}/block/{userToBeBlocked}', [UserController::class, 'block']);
    Route::get('/{user}/info/', [UserController::class, 'info']);
    Route::get('/notifications/{user}', [NotificationController::class, 'getUserNotifications']);
});
