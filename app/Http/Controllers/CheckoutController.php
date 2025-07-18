<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function pagar(Request $request)
    {
        $user = Auth::user();
        $assinaturaAtiva = $user->assinaturaAtiva;
        if ($assinaturaAtiva && $assinaturaAtiva->status === 'ativa' && $assinaturaAtiva->data_expiracao->isFuture()) {
            return redirect()->route('painel');
        }
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
            'external_reference' => Auth::user()->id, // ID do usuário para identificar no webhook
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
        $dataInicio = $assinatura->data_inicio;
        $agora = now();

        // Verifica se está dentro do prazo de 7 dias para reembolso
        if ($dataInicio && $agora->diffInDays($dataInicio) <= 7) {
            // Tenta estornar o pagamento via API do Mercado Pago
            $refundResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
                'X-Idempotency-Key' => (string) Str::uuid(), // Gera um UUID único
            ])->post("https://api.mercadopago.com/v1/payments/{$assinatura->payment_id}/refunds");

            if ($refundResponse->successful()) {
                $assinatura->cancelar();
                return back()->with('success', 'Assinatura cancelada e valor estornado com sucesso. O reembolso será processado pelo Mercado Pago.');
            } else {
                return back()->with('error', 'Erro ao estornar o pagamento: ' . $refundResponse->body());
            }
        } else {
            // Fora do prazo de reembolso, apenas cancela localmente
            $assinatura->cancelar();
            return back()->with('success', 'Assinatura cancelada. O prazo para reembolso automático já expirou.');
        }
    }
} 