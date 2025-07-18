<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function pagar(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $accessToken = config('services.mercadopago.access_token');
        $user = Auth::user();

        // Cria uma assinatura vinculada ao plano do Mercado Pago
        $subscription = [
            'preapproval_plan_id' => '2c9380849817d4bc01981b348b0e0153', // ID do plano criado no painel
            'payer_email' => $user->email,
            'back_url' => 'https://a5dfef01245f.ngrok-free.app/assinatura?success=true',
            'reason' => 'Assinatura Airbnb Controle',
            'external_reference' => $user->id
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post('https://api.mercadopago.com/preapproval', $subscription);

        if ($response->successful() && isset($response['init_point'])) {
            return redirect($response['init_point']);
        } else {
            $debugInfo = [
                'access_token' => $accessToken,
                'payer_email' => $user->email,
                'request' => $subscription,
                'response' => $response->json(),
            ];
            return back()->with('error', 'Erro ao criar assinatura recorrente: ' . json_encode($debugInfo));
        }
    }

    // Método para cancelar assinatura
    public function cancelar(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $assinatura = $user->assinaturaAtiva;

        if (!$assinatura) {
            return back()->with('error', 'Nenhuma assinatura ativa encontrada');
        }

        // Se for assinatura gratuita/manual, cancela só localmente
        if (str_starts_with($assinatura->payment_id, 'GRATUITA_')) {
            $assinatura->cancelar();
            return back()->with('success', 'Assinatura gratuita cancelada com sucesso.');
        }

        $accessToken = config('services.mercadopago.access_token');

        // Cancela a assinatura no Mercado Pago
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->put("https://api.mercadopago.com/preapproval/{$assinatura->payment_id}", [
            'status' => 'cancelled'
        ]);

        if ($response->successful()) {
            // Cancela localmente também
            $assinatura->cancelar();
            return back()->with('success', 'Assinatura cancelada com sucesso');
        } else {
            return back()->with('error', 'Erro ao cancelar assinatura: ' . $response->body());
        }
    }
} 