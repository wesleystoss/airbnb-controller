<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleMobileAuthController extends Controller
{
    public function login(Request $request)
    {
        $idToken = $request->input('idToken');

        $client = new Google_Client(['client_id' => config('services.google_android.client_id')]);
        $payload = $client->verifyIdToken($idToken);

        if ($payload) {
            $email = $payload['email'];
            $name = $payload['name'] ?? 'Usuário Google';

            // Procura ou cria o usuário
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name]
            );

            // Faz login de sessão web
            Auth::login($user);

            // Retorna token de API (opcional)
            $token = $user->createToken('mobile')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $user,
            ]);
        } else {
            return response()->json(['error' => 'Token inválido'], 401);
        }
    }
} 