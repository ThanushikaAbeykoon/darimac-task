<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            Log::error('Google Redirect Error: ', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect('/register')->withErrors(['error' => 'Failed to initiate Google login. Please check logs.']);
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            Log::info('Google User Data: ', ['user' => $googleUser->getRaw()]);

            // Check if user exists or create new
            $user = User::firstOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt('google-auth-password'), // Optional placeholder
                    'email_verified_at' => now(), // Auto-verify email from Google
                ]
            );

            // Ensure user is saved and has an ID
            if (!$user->wasRecentlyCreated && empty($user->google_id)) {
                $user->google_id = $googleUser->id;
                $user->save();
            }

            // Log in the user
            Auth::login($user, true);
            Log::info('User Logged In: ', ['user_id' => $user->id, 'email' => $user->email]);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            Log::error('Google Login Error: ', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect('/register')->withErrors(['error' => 'Google sign-up failed. Please try again or check the logs.']);
        }
    }
}
