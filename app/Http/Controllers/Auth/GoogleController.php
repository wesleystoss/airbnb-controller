<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Erro ao autenticar com o Google.']);
        }

        $user = User::where('email', $googleUser->getEmail())->first();
        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Usuário Google',
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(uniqid()), // senha aleatória
            ]);
        }
        Auth::login($user, true);
        return redirect()->route('home');
    }

    public function mobileLogin(Request $request)
    {
        $token = $request->input('token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['email' => 'Token Google não informado.']);
        }

        // Validar o token com a API do Google
        $client = new \Google_Client(['client_id' => config('services.google.client_id')]);
        $payload = $client->verifyIdToken($token);
        if (!$payload) {
            return redirect()->route('login')->withErrors(['email' => 'Token Google inválido.']);
        }

        $email = $payload['email'] ?? null;
        $name = $payload['name'] ?? ($payload['email'] ?? 'Usuário Google');
        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Não foi possível obter o e-mail do Google.']);
        }

        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            $user = \App\Models\User::create([
                'name' => $name,
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make(uniqid()),
            ]);
        }
        \Illuminate\Support\Facades\Auth::login($user, true);
        return redirect()->route('home');
    }
} 