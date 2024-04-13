<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CalendarController extends Controller
{

    public function openCalendar()
    {
        $user = auth()->user();
        $refreshToken = $user->google_refresh_token;
        $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken);

        try {
            $client = new Google_Client();
            $client->setAccessToken(['access_token' => $accessToken]);

            $service = new Google_Service_Calendar($client);
            
            $calendarId = 'primary';

            $results = $service->events->listEvents($calendarId);

            return $results->getItems();
        } catch (\Exception $ex) {
            return back()->withErrors('Unable to complete the request, due to this error' . $ex->getMessage());
        }
    }


    private function generateAccessTokenFromRefreshToken($refreshToken)
    {
        $newAccessToken = null;
        $googleClientId = config('services.google.client_id');
        $googleClientSecret = config('services.google.client_secret');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token',[
            'grant_type' => 'refresh_token',
            'client_id' => $googleClientId,
            'client_secret' => $googleClientSecret,
            'refresh_token' => $refreshToken,
        ]);

        if($response->successful()){
            $data = $response->json();
            $newAccessToken = $data['access_token'];
            $newRefreshToken = $data['refresh_token'] ?? $refreshToken;
        } else {
            $error = $response->json();
        }

        return $newAccessToken;

    }

}
