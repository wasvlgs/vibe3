<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'pseudo' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date', 'before:today'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cover_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Image validation
        ]);

        // Handle file upload (Store in local 'public/uploads/cover_picture')
        $coverPhotoPath = null;
        if ($request->hasFile('cover_picture')) {
            $file = $request->file('cover_picture');
            $coverPhotoName = time() . '_' . $file->getClientOriginalName();
            $coverPhotoPath = 'uploads/cover_picture/' . $coverPhotoName;
            $file->move(public_path('uploads/cover_picture'), $coverPhotoName);
        }

        // Create user and store the cover photo path
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'pseudo' => $request->pseudo,
            'city' => $request->city,
            'birthday' => $request->birthday,
            'password' => Hash::make($request->password),
            'cover_photo' => $coverPhotoPath, // Store file path in DB
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
