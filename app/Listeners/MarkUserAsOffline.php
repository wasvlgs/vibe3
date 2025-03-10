<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;

class MarkUserAsOffline
{
    public function handle(Logout $event)
    {
        $user = Auth::user();
        if ($user) {
            $user->update(['isActive' => false]);
            broadcast(new \App\Events\UserOnlineStatus($user));
        }
    }
}
