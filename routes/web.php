<?php

use App\Http\Controllers\AmiController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimpleQRcodeController;
use App\Http\Controllers\SocialiteController;
use http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailableName;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/dashboard',[DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/friends',[DashboardController::class, 'friends'])
    ->middleware(['auth', 'verified'])
    ->name('friends');
Route::get('/requests',[DashboardController::class, 'requests'])
    ->middleware(['auth', 'verified'])
    ->name('requests');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('hamzagbouri2004@gmail.com')->subject('Test Email');
    });
});
Route::post('/addFriend', [AmiController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('ami.envoyer');
Route::put('/ami/accepter/{id}', [AmiController::class, 'accepter'])->middleware(['auth', 'verified'])->name('ami.accepter');
Route::delete('/ami/annuler/{id}', [AmiController::class, 'annuler'])->middleware(['auth', 'verified'])->name('ami.annuler');
Route::post('/broadcasting/auth', function () {
    return Auth::user();
});
Route::post('/posts', [PostController::class, 'store'])->middleware(['auth','verified'])->name('posts.store');
Route::put('/post/{post}', [PostController::class, 'update'])->middleware(['auth','verified'])->name('posts.edit');
Route::get('/profile/{user:pseudo}', [ProfileController::class, 'show'])->middleware(['auth','verified'])->name('profile');
Route::delete('/post/{post}', [PostController::class, 'destroy'])->middleware(['auth','verified'])->name('posts.destroy');
// Routes for liking and commenting on posts
Route::post('posts/{post}/like', [PostController::class, 'like'])->middleware(['auth','verified'])->name('posts.like');
Route::post('posts/{post}/comment', [PostController::class, 'comment'])->middleware(['auth','verified'])->name('posts.comment');
Route::get("simple-qrcode", [SimpleQrcodeController::class, 'generate']);

Route::middleware('auth')->group(function () {
    // Route to view all conversations
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    // Route to view a specific conversation
    Route::get('/chat/{pseudo}', [ChatController::class, 'show'])->name('chat.show');

    // Route to send a message
    Route::post('/chat/{conversation_id}/message', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');

    // Route to mark messages as read
    Route::post('/chat/{conversation_id}/markAsRead', [ChatController::class, 'markAsRead'])->name('chat.markAsRead');
});

Route::middleware('web')->group(function () {
    Route::get('login/google', [SocialiteController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
    Route::get('login/facebook', [SocialiteController::class, 'redirectToFacebook'])->name('login.facebook');
    Route::get('login/facebook/callback', [SocialiteController::class, 'handleFacebookCallback']);

});
Route::get('/generate-invite-link', [AmiController::class, 'generateInviteLink'])->name('generate.invite.link');
Route::get('/generate-qr-code', [AmiController::class, 'generateQRCode'])->name('generate.qr.code');
Route::get('/accept-invitation', [AmiController::class, 'acceptInvitation'])->name('accept.invitation');
require __DIR__.'/auth.php';


use App\Models\User;
use Carbon\Carbon;


Route::get('/online-users', function () {
    $onlineUsers = User::where('last_seen', '>=', Carbon::now()->subMinutes(5))->get(['id', 'name', 'avatar']);
    return response()->json($onlineUsers);
})->name('online.users');



Route::post('/send-message', [ChatController::class, 'send'])->name('chatify.send');
