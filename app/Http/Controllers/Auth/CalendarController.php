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
use Illuminate\Support\Facades\Log;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
class CalendarController extends Controller
{
    private $client;

    private function formatToRFC3339($dateTimeString)
    {
        $dateTime = Carbon::parse($dateTimeString);
        return $dateTime->toRfc3339String();
    }
    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(config('services.google'));
    }

    public function index()
    {
        $user = auth()->user();
        $refreshToken = $user->google_refresh_token;
        $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken);

        try {
            $this->client->setAccessToken(['access_token' => $accessToken]);

            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';

            $events = $service->events->listEvents($calendarId);

            return view('auth.calendar.index', compact('events'));
        } catch (\Exception $ex) {
            return back()->withErrors('Unable to complete the request, due to this error: ' . $ex->getMessage());
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


    public function create()
    {
        return view('auth.calendar.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $refreshToken = $user->google_refresh_token;
        $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken);

        try {
            $this->client->setAccessToken(['access_token' => $accessToken]);

            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';

            $startDateTime = $request->start . 'T' . $request->start_time . ':00-03:00';
            $endDateTime = $request->end . 'T' . $request->end_time . ':00-03:00';

            $event = new Google_Service_Calendar_Event([
                'summary' => $request->title,
                'description' => $request->description,
                'start' => ['dateTime' => $startDateTime],
                'end' => ['dateTime' => $endDateTime],
                'reminders' => ['useDefault' => true],
            ]);

            $results = $service->events->insert($calendarId, $event);

            if (!$results) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }

            return response()->json(['status' => 'success', 'message' => 'Event Created']);
        } catch (\Exception $ex) {
            return back()->withErrors('Unable to complete the request, due to this error: ' . $ex->getMessage());
        }
    }

    public function show(string $id)
    {
        $user = auth()->user();
        $refreshToken = $user->google_refresh_token;
        $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken);

        try {
            $this->client->setAccessToken(['access_token' => $accessToken]);

            $service = new Google_Service_Calendar($this->client);

            $event = $service->events->get('primary', $id);

            return view('auth.calendar.show', compact('event'));
        } catch (\Exception $ex) {
            return back()->withErrors('Unable to complete the request, due to this error: ' . $ex->getMessage());
        }
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $refreshToken = $user->google_refresh_token;
        $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken);

        try {
            $this->client->setAccessToken(['access_token' => $accessToken]);

            $service = new Google_Service_Calendar($this->client);

            // Recuperando o evento da API
            $event = $service->events->get('primary', $id);

            // Atualizando os detalhes do evento com os dados do formulário
            $event->setSummary($request->title);
            $event->setDescription($request->description);

            // Convertendo as datas e horas para o formato RFC3339 ( Necessário API )
            $startDateTime = new EventDateTime();
            $startDateTime->setDateTime($this->formatToRFC3339($request->start));
            $event->setStart($startDateTime);

            $endDateTime = new EventDateTime();
            $endDateTime->setDateTime($this->formatToRFC3339($request->end));
            $event->setEnd($endDateTime);

            // Atualizando o evento
            $updatedEvent = $service->events->update('primary', $event->getId(), $event);

            if (!$updatedEvent) {
                return back()->withErrors('Something went wrong while updating the event.');
            }

            return back()->withSuccess('Event updated successfully.');

        } catch (\Exception $ex) {
            return back()->withErrors('Unable to complete the request: ' . $ex->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $user = auth()->user();
        $refreshToken = $user->google_refresh_token;
        $accessToken = $this->generateAccessTokenFromRefreshToken($refreshToken);

        try {
            $this->client->setAccessToken(['access_token' => $accessToken]);

            $service = new Google_Service_Calendar($this->client);

            $service->events->delete('primary', $id);

            return redirect()->route('calendar.index')->withSuccess('Event deleted successfully.');
        } catch (\Exception $ex) {
            return back()->withErrors('Unable to complete the request: ' . $ex->getMessage());
        }
    }

}
