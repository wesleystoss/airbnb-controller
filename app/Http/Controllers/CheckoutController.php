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
                'title' => 'Assinatura Airbnb Controle - 12 meses de acesso ilimitado, relatórios automáticos, agenda integrada e suporte humano',
                'description' => 'Tenha controle total dos seus imóveis, locações e finanças em um só lugar. Assinatura anual do Airbnb Controle: relatórios automáticos, agenda integrada, suporte humano e cancelamento fácil. Pagamento único, acesso ilimitado por 12 meses.',
                'quantity' => 1,
                'currency_id' => 'BRL',
                'unit_price' => 1,
            ]],
            'payer' => [
                'email' => Auth::user()->email,
            ],
            'external_reference' => Auth::user()->id, // ID do usuário para identificar no webhook
            'back_urls' => [
                'success' => 'https://airbnb.wesleystoss.com.br/',
                'failure' => 'https://airbnb.wesleystoss.com.br/assinatura',
                'pending' => 'https://airbnb.wesleystoss.com.br/assinatura',
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

        // Se não veio motivo, exibe formulário
        if ($request->isMethod('get')) {
            $valorEstorno = null;
            $tipoEstorno = null;
            $mensagemEstorno = null;
            if (!str_starts_with($assinatura->payment_id, 'GRATUITA_')) {
                $dataInicio = $assinatura->data_inicio;
                $dataExpiracao = $assinatura->data_expiracao;
                $agora = now();
                $totalDiasCiclo = $dataInicio->diffInDays($dataExpiracao) ?: 1;
                $diasDesdeInicio = $dataInicio ? $dataInicio->diffInDays($agora, false) : null;
                if ($dataInicio && $diasDesdeInicio !== null && $diasDesdeInicio >= 0 && $diasDesdeInicio < 7) {
                    $valorEstorno = $assinatura->valor;
                    $tipoEstorno = 'integral';
                    $mensagemEstorno = 'Você receberá o estorno integral de R$ ' . number_format($valorEstorno, 2, ',', '.');
                } else {
                    $diasRestantes = $agora->diffInDays($dataExpiracao, false);
                    if ($diasRestantes > 0) {
                        $valorDiario = $assinatura->valor / $totalDiasCiclo;
                        $valorEstorno = round($valorDiario * $diasRestantes, 2);
                        $tipoEstorno = 'parcial';
                        $mensagemEstorno = 'Você receberá o estorno parcial de R$ ' . number_format($valorEstorno, 2, ',', '.') . ' referente aos dias não utilizados.';
                    } else {
                        $tipoEstorno = 'nenhum';
                        $mensagemEstorno = 'Não há direito a estorno, pois não restam dias não utilizados.';
                    }
                }
            } else {
                $tipoEstorno = 'nenhum';
                $mensagemEstorno = 'Assinaturas gratuitas não possuem estorno.';
            }
            return view('assinatura.cancelar', compact('assinatura', 'valorEstorno', 'tipoEstorno', 'mensagemEstorno'));
        }

        // Valida motivo
        $request->validate([
            'motivo' => 'required|string|min:10',
        ], [
            'motivo.required' => 'Por favor, explique o motivo do cancelamento.',
            'motivo.min' => 'Explique o motivo com pelo menos 10 caracteres.',
        ]);

        $valorEstorno = null;
        $estornoRealizado = false;
        $statusRequerimento = 'diferido';

        // Se for assinatura gratuita/manual, cancela só localmente
        if (str_starts_with($assinatura->payment_id, 'GRATUITA_')) {
            $assinatura->cancelar();
        } else {
            $accessToken = config('services.mercadopago.access_token');
            $dataInicio = $assinatura->data_inicio;
            $dataExpiracao = $assinatura->data_expiracao;
            $agora = now();

            // Verifica se está dentro do prazo de 7 dias para reembolso
            $diasDesdeInicio = $dataInicio ? $dataInicio->diffInDays($agora, false) : null;
            if ($dataInicio && $diasDesdeInicio !== null && $diasDesdeInicio >= 0 && $diasDesdeInicio < 7) {
                // Tenta estornar o pagamento total via API do Mercado Pago
                $refundResponse = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-Idempotency-Key' => (string) \Illuminate\Support\Str::uuid(),
                ])->post("https://api.mercadopago.com/v1/payments/{$assinatura->payment_id}/refunds");

                if ($refundResponse->successful()) {
                    $valorEstorno = $assinatura->valor;
                    $estornoRealizado = true;
                    $assinatura->cancelar();
                    $msg = 'Assinatura cancelada e valor estornado com sucesso. O reembolso será processado pelo Mercado Pago.';
                } else {
                    $assinatura->cancelar();
                    $msg = 'Assinatura cancelada. O prazo para reembolso automático já expirou ou houve erro no estorno.';
                }
            } else {
                // Fora do prazo de reembolso, calcular estorno parcial
                $diasRestantes = $agora->diffInDays($dataExpiracao, false);
                $totalDiasCiclo = $dataInicio->diffInDays($dataExpiracao) ?: 1;
                if ($diasRestantes > 0) {
                    $valorDiario = $assinatura->valor / $totalDiasCiclo;
                    $valorEstorno = round($valorDiario * $diasRestantes, 2);
                    // Tenta estornar o valor parcial via API do Mercado Pago
                    $refundResponse = \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => 'application/json',
                        'X-Idempotency-Key' => (string) \Illuminate\Support\Str::uuid(),
                    ])->post("https://api.mercadopago.com/v1/payments/{$assinatura->payment_id}/refunds", [
                        'amount' => $valorEstorno
                    ]);
                    if ($refundResponse->successful()) {
                        $estornoRealizado = true;
                        $msg = 'Assinatura cancelada e valor parcial estornado com sucesso. O reembolso proporcional será processado pelo Mercado Pago.';
                    } else {
                        $msg = 'Assinatura cancelada. Não foi possível realizar o estorno parcial automático.';
                    }
                } else {
                    $msg = 'Assinatura cancelada. Não há dias restantes para estorno.';
                }
                $assinatura->cancelar();
            }
        }

        // Cria requerimento
        \App\Models\Requerimento::create([
            'assinatura_id' => $assinatura->id,
            'motivo' => $request->motivo,
            'status' => $statusRequerimento,
            'valor_estorno' => $valorEstorno,
            'estorno_realizado' => $estornoRealizado,
        ]);

        return redirect()->route('assinatura')->with('success', $msg ?? 'Assinatura cancelada.');
    }
} 