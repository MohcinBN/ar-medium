<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SocialiteController extends Controller
{
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialiteUser = Socialite::driver($provider)->stateless()->user();
            
            $user = User::firstOrCreate(
                ['email' => $socialiteUser->email],
                [
                    'name' => $socialiteUser->name,
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(24)),
                ],
            );
            
            Auth::login($user);
            
            return redirect('/dashboard');
            
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect('/login')->with('error', 'Something went wrong!');
        }
    }
}
