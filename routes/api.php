<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Enable broadcasting authentication for private channels
    Broadcast::routes(['middleware' => ['auth:sanctum']]);

    // Chat Routes
    Route::get('/chat', [ChatController::class, 'index']); // List all conversations and friends
    Route::get('/chat/{pseudo}', [ChatController::class, 'show']); // Show conversation with a friend
    Route::post('/chat/{conversation_id}/send', [ChatController::class, 'sendMessage']); // Send a message
    Route::post('/chat/{conversation_id}/mark-as-read', [ChatController::class, 'markAsRead']); // Mark messages as read
});
