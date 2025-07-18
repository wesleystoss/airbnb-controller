<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function pagar(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        // Use o access token do .env via config
        $accessToken = config('services.mercadopago.access_token');

        $preference = [
            'items' => [[
                'title' => 'Assinatura Airbnb Controle',
                'quantity' => 1,
                'currency_id' => 'BRL',
                'unit_price' => 1,
            ]],
            'payer' => [
                'email' => Auth::user()->email,
            ],
            'back_urls' => [
                'success' => 'https://a5dfef01245f.ngrok-free.app/',
                'failure' => 'https://a5dfef01245f.ngrok-free.app/assinatura',
                'pending' => 'https://a5dfef01245f.ngrok-free.app/assinatura',
            ],
            'auto_return' => 'approved',
        ];

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->withBody(json_encode($preference), 'application/json')
          ->post('https://api.mercadopago.com/checkout/preferences');

        if ($response->successful() && isset($response['init_point'])) {
            return redirect($response['init_point']);
        } else {
            $debugInfo = [
                'access_token' => $accessToken,
                'payer_email' => Auth::user()->email,
                'request' => $preference,
                'response' => $response->json(),
            ];
            return back()->with('error', 'Erro ao criar preferência de pagamento: ' . json_encode($debugInfo));
        }
    }
} 