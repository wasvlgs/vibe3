<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\UserDTO;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $friends = $user->friends()->get();
        $posts = Post::whereIn('user_id', $friends->pluck('id')->push($user->id))
            ->orderBy('created_at', 'desc') // Sort by the latest post
            ->get();

        return view('dashboard', compact('friends','posts'));
    }

    public function friends(Request $request)
    {
        // Get the search term from the request
        $search = $request->input('search');

        // Query users based on search term for 'name' or 'city'
        if ($search) {
            $users = User::where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('city', 'like', '%' . $search . '%')
                ->get();
        } else {
            // If no search term, fetch all users
            $users = User::all();
        }

        $user = auth()->user();

        // Fetch all received and sent requests, regardless of status
        $receivedRequests = $user->receivedRequests()->with('sender')->get();
        $sentRequests = $user->sentRequests()->with('receiver')->get();

        return view('friends', compact('users', 'receivedRequests', 'sentRequests'));
    }
}
