<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Laravel\Socialite\Facades\Socialite; // Importa a fachada do Socialite para lidar com autenticação social
use App\Http\Controllers\Controller;
use App\Models\User; // Importa o modelo de usuário
use Illuminate\Http\Request;

class SocialController extends Controller
{
    // Redireciona para a página de autenticação do Google
    public function redirectOnGoogle()
    {
        return Socialite::driver('google') // Define o driver como 'google' para usar a autenticação do Google
            ->with(['access_type' => 'offline']) // Define o tipo de acesso como offline para obter um token de atualização
            ->scopes('https://www.googleapis.com/auth/calendar') // Define os escopos de acesso para acesso à API do Google Calendar
            ->redirect(); // Redireciona para a página de autenticação do Google
    }

    // Obtém os detalhes da conta do Google do usuário autenticado
    public function openGoogleAccountDetails()
    {
        $user = auth()->user(); // Obtém o usuário autenticado atualmente
        $googleUser = Socialite::driver('google')->user(); // Obtém os detalhes da conta do Google do usuário autenticado

        // Se o usuário estiver autenticado
        if ($user) {
            // Atualiza as informações do usuário com os detalhes da conta do Google
            $user->update([
                'google_id' => $googleUser->id, // ID do usuário no Google
                'google_access_token' => $googleUser->token, // Token de acesso do Google
                'google_refresh_token' => $googleUser->refreshToken // Token de atualização do Google
            ]);
        }

        // Exibe uma mensagem de sucesso para o usuário
        session()->flash('alert-success', 'Account linked successfully!');

        // Redireciona para a página de painel
        return to_route('dashboard');
    }
}
