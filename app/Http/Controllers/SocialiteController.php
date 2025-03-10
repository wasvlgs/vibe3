<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialiteController extends Controller
{
    // Redirect the user to the Google authentication page.
    public function redirectToGoogle()
    {
        Log::info('Redirecting to Google authentication page.');
        return Socialite::driver('google')->redirect();
    }

    // Handle the callback from Google.
    public function handleGoogleCallback()
    {
        try {
            // Retrieve user data from Google.
            $googleUser = Socialite::driver('google')->user();
            Log::info('Google user data received:', [
                'name'  => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'id'    => $googleUser->getId(),
            ]);

            // Check if the user already exists.
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                Log::info('User exists. Logging in user: ' . $user->email);
                Auth::login($user);
            } else {
                Log::info('User does not exist. Creating new user with email: ' . $googleUser->getEmail());
                // Create a new user with default values for required fields.
                $user = User::create([
                    'name'       => $googleUser->getName(),
                    'email'      => $googleUser->getEmail(),
                    'pseudo'     => Str::slug($googleUser->getName()), // Generate a default pseudo
                    'city'       => 'Unknown',                         // Default city value
                    'birthday'   => '2000-01-01',                        // Default birthday (adjust as needed)
                    // Use a random password since the user is authenticating via Google.
                    'password'   => bcrypt(uniqid()),
                    'cover_photo'=> $googleUser->getAvatar() ?? 'uploads/cover_picture/none.png',
                ]);
                Log::info('New user created with email: ' . $user->email);
                Auth::login($user);
            }

            if (Auth::check()) {
                Log::info('User is now authenticated: ' . Auth::user()->email);
            } else {
                Log::error('User authentication failed.');
            }

            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            Log::error('Error during Google callback: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Google login failed. Please try again.');
        }
    }
    public function redirectToFacebook()
    {
        Log::info('Redirecting to Facebook authentication page.');
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            Log::info('Facebook user data received:', [
                'name'  => $facebookUser->getName(),
                'email' => $facebookUser->getEmail(),
                'id'    => $facebookUser->getId(),
            ]);

            // Vérifier si l'utilisateur existe déjà.
            $user = User::where('email', $facebookUser->getEmail())->first();

            if ($user) {
                Log::info('User exists. Logging in user: ' . $user->email);
                Auth::login($user);
            } else {
                Log::info('User does not exist. Creating new user with email: ' . $facebookUser->getEmail());
                $user = User::create([
                    'name'       => $facebookUser->getName(),
                    'email'      => $facebookUser->getEmail(),
                    'pseudo'     => Str::slug($facebookUser->getName()),
                    'city'       => 'Unknown',
                    'birthday'   => '2000-01-01',
                    'password'   => bcrypt(uniqid()),
                    'cover_photo'=> $facebookUser->getAvatar() ?? 'uploads/cover_picture/none.png',
                ]);
                Log::info('New user created with email: ' . $user->email);
                Auth::login($user);
            }

            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            Log::error('Error during Facebook callback: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Facebook login failed. Please try again.');
        }
    }
}
