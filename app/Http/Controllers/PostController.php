<?php

namespace App\Http\Controllers;

use App\Models\Commantaire;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Show the form for creating a new post
    public function create()
    {
        return view('dashboard');
    }

    // Store a new post
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'titre' => 'required|max:255',
            'description' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle the image upload (if any)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $coverPhotoName = time() . '_' . $file->getClientOriginalName();
            $imagePath = 'uploads/image/' . $coverPhotoName;
            $file->move(public_path('uploads/image'), $coverPhotoName);
        } else {
            $imagePath = null;
        }

        // Create the post
        Post::create([
            'user_id' => Auth::id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'content' => $request->content,
            'image' => $imagePath,
            'date' => now(),
        ]);

        // Redirect back to the dashboard
        return redirect()->route('dashboard')->with('success', 'Post created successfully!');
    }
    public function like(Post $post)
    {
        // Check if the user has already liked the post
        $like = Like::where('user_id', auth()->id())->where('post_id', $post->id)->first();

        if ($like) {
            // If already liked, unlike it
            $like->delete();
        } else {
            // If not liked, like the post
            Like::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
            ]);
        }

        return redirect()->back(); // Redirect back to the dashboard
    }

    public function comment(Post $post)
    {
        // Validate and create a new comment
        request()->validate([
            'content' => 'required|string|max:255',
        ]);
        print_r(request('content'));
        Commantaire::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'commantaire' => request('content'),
            'date' => now(),
        ]);

        return redirect()->back(); // Redirect back to the dashboard
    }
    public function update(Request $request, Post $post)
    {
        // Validate input
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Update the post
        $post->titre = $validatedData['titre'];
        $post->description = $validatedData['description'];
        $post->content = $validatedData['content'];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $coverPhotoName = time() . '_' . $file->getClientOriginalName();
            $imagePath = 'uploads/image/' . $coverPhotoName;
            $file->move(public_path('uploads/image'), $coverPhotoName);
            $post->image = $imagePath;
        }

        $post->save();

        return redirect()->route('dashboard')->with('success', 'Post updated successfully');
    }
    public function destroy(Post $post)
    {
        // Check if the authenticated user is the owner of the post
        if (Auth::id() !== $post->user_id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to delete this post');
        }

        // Delete the post
        $post->delete();

        // Redirect back with success message
        return redirect()->route('dashboard')->with('success', 'Post deleted successfully');
    }


}

