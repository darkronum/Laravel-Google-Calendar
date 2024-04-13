<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function redirectOnGoogle()
    {
        return Socialite::driver('google')
            ->with(['access_type' => 'offline'])
            ->scopes('https://www.googleapis.com/auth/calendar')
            ->redirect();
    }
    public function openGoogleAccountDetails()
    {
        $user = auth()->user();
        $googleUser = Socialite::driver('google')->user();
        if($user){
            $user->update([
                'google_id' => $googleUser->id,
                'google_access_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken
            ]);
        }

        session()->flash('alert-success', 'Account linked successfully!');

        return to_route('dashboard');

    }

}

