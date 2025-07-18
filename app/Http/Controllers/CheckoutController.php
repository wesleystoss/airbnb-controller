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
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $accessToken = config('services.mercadopago.access_token');
        $user = Auth::user();

        // Cria uma assinatura vinculada ao plano do Mercado Pago
        $subscription = [
            'preapproval_plan_id' => '2c9380849817d4bc01981b348b0e0153', // ID do plano criado no painel
            'payer_email' => $user->email,
            'back_url' => 'https://airbnb.wesleystoss.com.br/assinatura?success=true',
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
        $dataInicio = $assinatura->data_inicio;
        $agora = now();

        // Verifica se está dentro do prazo de 7 dias para reembolso
        if ($dataInicio && $agora->diffInDays($dataInicio) <= 7) {
            // Tenta estornar o pagamento via API do Mercado Pago
            $refundResponse = Http::withHeaders([
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