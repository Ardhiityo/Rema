<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AvatarGenerator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $google_user = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['google_id' => $google_user->getId()],
            [
                'name' => $google_user->getName(),
                'email' => $google_user->getEmail(),
                'google_id' => $google_user->getId(),
                'password' => Str::random(8),
                'email_verified_at' => now(),
                'avatar' => AvatarGenerator::generate()
            ]
        );

        Auth::login($user);

        if (is_null($user->author)) {
            $user->author()->create();
        }

        $user->assignRole('contributor');

        return redirect()->route('dashboard');
    }
}
