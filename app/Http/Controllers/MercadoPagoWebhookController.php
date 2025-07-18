<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            // Loga o payload recebido para debug
            Log::info('Webhook Mercado Pago recebido:', [
                'headers' => $request->headers->all(),
                'body' => $request->all(),
                'query' => $request->query->all()
            ]);

            // Verifica se é um webhook de pagamento
            if ($request->has('type') && $request->type === 'payment') {
                $paymentId = $request->data['id'] ?? null;
                
                if ($paymentId) {
                    // Busca detalhes do pagamento na API do Mercado Pago
                    $paymentDetails = $this->getPaymentDetails($paymentId);
                    
                    if ($paymentDetails) {
                        Log::info('Detalhes do pagamento:', $paymentDetails);
                        
                        // Processa o status do pagamento
                        $this->processPaymentStatus($paymentDetails);
                    }
                }
            }

            // Sempre retorna 200 para o Mercado Pago
            return response()->json(['status' => 'ok'], 200);
            
        } catch (\Exception $e) {
            Log::error('Erro no webhook Mercado Pago:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Mesmo com erro, retorna 200 para evitar reenvios
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    private function getPaymentDetails($paymentId)
    {
        try {
            $accessToken = config('services.mercadopago.access_token');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

            if ($response->successful()) {
                return $response->json();
            }
            
            Log::error('Erro ao buscar detalhes do pagamento:', [
                'payment_id' => $paymentId,
                'response' => $response->json()
            ]);
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Exceção ao buscar detalhes do pagamento:', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    private function processPaymentStatus($paymentDetails)
    {
        $status = $paymentDetails['status'] ?? null;
        $paymentId = $paymentDetails['id'] ?? null;
        $externalReference = $paymentDetails['external_reference'] ?? null;
        
        Log::info('Processando status do pagamento:', [
            'payment_id' => $paymentId,
            'status' => $status,
            'external_reference' => $externalReference
        ]);

        switch ($status) {
            case 'approved':
                Log::info('Pagamento aprovado:', ['payment_id' => $paymentId]);
                // Aqui você pode adicionar lógica para ativar a assinatura
                // Por exemplo: atualizar status do usuário, enviar email, etc.
                break;
                
            case 'pending':
                Log::info('Pagamento pendente:', ['payment_id' => $paymentId]);
                break;
                
            case 'in_process':
                Log::info('Pagamento em análise:', ['payment_id' => $paymentId]);
                break;
                
            case 'rejected':
                Log::info('Pagamento rejeitado:', ['payment_id' => $paymentId]);
                break;
                
            case 'cancelled':
                Log::info('Pagamento cancelado:', ['payment_id' => $paymentId]);
                break;
                
            case 'refunded':
                Log::info('Pagamento reembolsado:', ['payment_id' => $paymentId]);
                break;
                
            default:
                Log::info('Status desconhecido:', ['payment_id' => $paymentId, 'status' => $status]);
                break;
        }
    }
} 