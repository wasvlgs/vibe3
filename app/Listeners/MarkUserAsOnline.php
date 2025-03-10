<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class MarkUserAsOnline
{
    public function handle(Login $event)
    {
        $user = Auth::user();
        if ($user) {
            $user->update(['isActive' => true]);
            broadcast(new \App\Events\UserOnlineStatus($user));
        }
    }
}
