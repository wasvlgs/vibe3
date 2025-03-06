<?php

use App\Http\Controllers\AmiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
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
//Route::post('/broadcasting/auth', function (Illuminate\Http\Request $request) {
//    return Broadcast::auth($request);
//});
Route::post('/posts', [PostController::class, 'store'])->middleware(['auth','verified'])->name('posts.store');
Route::put('/post/{post}', [PostController::class, 'update'])->middleware(['auth','verified'])->name('posts.edit');
Route::get('/profile/{user:pseudo}', [ProfileController::class, 'show'])->middleware(['auth','verified'])->name('profile');
Route::delete('/post/{post}', [PostController::class, 'destroy'])->middleware(['auth','verified'])->name('posts.destroy');
// Routes for liking and commenting on posts
Route::post('posts/{post}/like', [PostController::class, 'like'])->middleware(['auth','verified'])->name('posts.like');
Route::post('posts/{post}/comment', [PostController::class, 'comment'])->middleware(['auth','verified'])->name('posts.comment');
Route::get('chat', function (){
    return view('chat');
});
Route::middleware('web')->group(function () {
    Route::get('login/google', [SocialiteController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('login/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
});

Broadcast::channel('test-channel', function () {
    return true;
});


require __DIR__.'/auth.php';


