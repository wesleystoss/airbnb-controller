<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Assinatura;
use App\Models\User;

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

            // Verifica se é um webhook de assinatura recorrente
            if ($request->has('type') && $request->type === 'subscription_preapproval') {
                $subscriptionId = $request->data['id'] ?? null;
                
                if ($subscriptionId) {
                    // Busca detalhes da assinatura na API do Mercado Pago
                    $subscriptionDetails = $this->getSubscriptionDetails($subscriptionId);
                    
                    if ($subscriptionDetails) {
                        Log::info('Detalhes da assinatura recorrente:', $subscriptionDetails);
                        
                        // Processa o status da assinatura
                        $this->processSubscriptionStatus($subscriptionDetails);
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

    private function getSubscriptionDetails($subscriptionId)
    {
        try {
            $accessToken = config('services.mercadopago.access_token');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->get("https://api.mercadopago.com/preapproval/{$subscriptionId}");

            if ($response->successful()) {
                return $response->json();
            }
            
            Log::error('Erro ao buscar detalhes da assinatura:', [
                'subscription_id' => $subscriptionId,
                'response' => $response->json()
            ]);
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Exceção ao buscar detalhes da assinatura:', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    private function processSubscriptionStatus($subscriptionDetails)
    {
        $status = $subscriptionDetails['status'] ?? null;
        $subscriptionId = $subscriptionDetails['id'] ?? null;
        $externalReference = $subscriptionDetails['external_reference'] ?? null;
        $payerEmail = $subscriptionDetails['payer_email'] ?? null;
        $autoRecurring = $subscriptionDetails['auto_recurring'] ?? null;
        
        Log::info('Processando status da assinatura recorrente:', [
            'subscription_id' => $subscriptionId,
            'status' => $status,
            'external_reference' => $externalReference,
            'payer_email' => $payerEmail,
            'auto_recurring' => $autoRecurring
        ]);

        switch ($status) {
            case 'authorized':
                Log::info('✅ Assinatura recorrente autorizada:', [
                    'subscription_id' => $subscriptionId,
                    'payer_email' => $payerEmail
                ]);
                $this->handleAuthorizedSubscription($subscriptionDetails);
                break;
                
            case 'pending':
                Log::info('⏳ Assinatura recorrente pendente:', [
                    'subscription_id' => $subscriptionId,
                    'payer_email' => $payerEmail
                ]);
                $this->handlePendingSubscription($subscriptionDetails);
                break;
                
            case 'cancelled':
                Log::info('🚫 Assinatura recorrente cancelada:', [
                    'subscription_id' => $subscriptionId,
                    'payer_email' => $payerEmail
                ]);
                $this->handleCancelledSubscription($subscriptionDetails);
                break;
                
            case 'paused':
                Log::info('⏸️ Assinatura recorrente pausada:', [
                    'subscription_id' => $subscriptionId,
                    'payer_email' => $payerEmail
                ]);
                $this->handlePausedSubscription($subscriptionDetails);
                break;
                
            default:
                Log::info('❓ Status de assinatura desconhecido:', [
                    'subscription_id' => $subscriptionId,
                    'status' => $status
                ]);
                break;
        }
    }

    private function processPaymentStatus($paymentDetails)
    {
        $status = $paymentDetails['status'] ?? null;
        $paymentId = $paymentDetails['id'] ?? null;
        $externalReference = $paymentDetails['external_reference'] ?? null;
        $statusDetail = $paymentDetails['status_detail'] ?? null;
        $payerEmail = $paymentDetails['payer']['email'] ?? null;
        $transactionAmount = $paymentDetails['transaction_amount'] ?? null;
        
        Log::info('Processando status do pagamento:', [
            'payment_id' => $paymentId,
            'status' => $status,
            'status_detail' => $statusDetail,
            'external_reference' => $externalReference,
            'payer_email' => $payerEmail,
            'amount' => $transactionAmount
        ]);

        switch ($status) {
            case 'approved':
                Log::info('✅ Pagamento aprovado:', [
                    'payment_id' => $paymentId,
                    'payer_email' => $payerEmail,
                    'amount' => $transactionAmount
                ]);
                // Aqui você pode adicionar lógica para ativar a assinatura
                // Por exemplo: atualizar status do usuário, enviar email, etc.
                $this->handleApprovedPayment($paymentDetails);
                break;
                
            case 'pending':
                Log::info('⏳ Pagamento pendente:', [
                    'payment_id' => $paymentId,
                    'status_detail' => $statusDetail,
                    'payer_email' => $payerEmail
                ]);
                $this->handlePendingPayment($paymentDetails);
                break;
                
            case 'in_process':
                Log::info('🔍 Pagamento em análise:', [
                    'payment_id' => $paymentId,
                    'status_detail' => $statusDetail,
                    'payer_email' => $payerEmail
                ]);
                $this->handleInProcessPayment($paymentDetails);
                break;
                
            case 'rejected':
                Log::info('❌ Pagamento rejeitado:', [
                    'payment_id' => $paymentId,
                    'status_detail' => $statusDetail,
                    'payer_email' => $payerEmail,
                    'reason' => $this->getRejectionReason($statusDetail)
                ]);
                $this->handleRejectedPayment($paymentDetails);
                break;
                
            case 'cancelled':
                Log::info('🚫 Pagamento cancelado:', [
                    'payment_id' => $paymentId,
                    'status_detail' => $statusDetail,
                    'payer_email' => $payerEmail
                ]);
                $this->handleCancelledPayment($paymentDetails);
                break;
                
            case 'refunded':
                Log::info('💰 Pagamento reembolsado:', [
                    'payment_id' => $paymentId,
                    'status_detail' => $statusDetail,
                    'payer_email' => $payerEmail
                ]);
                $this->handleRefundedPayment($paymentDetails);
                break;
                
            default:
                Log::info('❓ Status desconhecido:', [
                    'payment_id' => $paymentId,
                    'status' => $status,
                    'status_detail' => $statusDetail
                ]);
                break;
        }
    }

    private function getRejectionReason($statusDetail)
    {
        $reasons = [
            'cc_rejected_bad_filled_date' => 'Data de vencimento incorreta',
            'cc_rejected_bad_filled_other' => 'Dados do cartão incorretos',
            'cc_rejected_bad_filled_security_code' => 'Código de segurança incorreto',
            'cc_rejected_blacklist' => 'Cartão não autorizado',
            'cc_rejected_call_for_authorize' => 'Ligue para autorizar o pagamento',
            'cc_rejected_card_disabled' => 'Cartão desabilitado',
            'cc_rejected_duplicated_payment' => 'Pagamento duplicado',
            'cc_rejected_high_risk' => 'Pagamento rejeitado por risco',
            'cc_rejected_insufficient_amount' => 'Saldo insuficiente',
            'cc_rejected_invalid_installments' => 'Parcelamento não disponível',
            'cc_rejected_max_attempts' => 'Limite de tentativas excedido',
            'cc_rejected_other_reason' => 'Rejeitado por outro motivo',
            'cc_rejected_card_error' => 'Erro no cartão',
            'cc_rejected_insufficient_data' => 'Dados insuficientes',
            'rejected_by_bank' => 'Rejeitado pelo banco',
            'rejected_insufficient_data' => 'Dados insuficientes',
            'rejected_other_reason' => 'Rejeitado por outro motivo',
            'rejected_high_risk' => 'Rejeitado por risco alto',
            'rejected_bad_filled_security_code' => 'Código de segurança incorreto',
            'rejected_bad_filled_date' => 'Data incorreta',
            'rejected_bad_filled_other' => 'Dados incorretos'
        ];

        return $reasons[$statusDetail] ?? 'Motivo não especificado';
    }

    private function handleApprovedPayment($paymentDetails)
    {
        $paymentId = $paymentDetails['id'] ?? null;
        $externalReference = $paymentDetails['external_reference'] ?? null;
        $transactionAmount = $paymentDetails['transaction_amount'] ?? null;
        
        Log::info('🎉 Processando pagamento aprovado - Ativando assinatura', [
            'payment_id' => $paymentId,
            'external_reference' => $externalReference,
            'amount' => $transactionAmount
        ]);

        try {
            // Busca o usuário pelo external_reference (ID do usuário)
            $user = User::find($externalReference);
            
            if (!$user) {
                Log::error('❌ Usuário não encontrado para o external_reference', ['external_reference' => $externalReference]);
                return;
            }

            // Verifica se já existe assinatura para este payment_id
            $assinaturaExistente = Assinatura::where('payment_id', $paymentId)->first();
            if ($assinaturaExistente) {
                Log::info('✅ Pagamento já processado anteriormente', [
                    'payment_id' => $paymentId,
                    'assinatura_id' => $assinaturaExistente->id
                ]);
                return;
            }

            // Cancela assinaturas anteriores do usuário
            Assinatura::where('user_id', $user->id)
                     ->where('status', 'ativa')
                     ->update(['status' => 'cancelada']);

            // Cria nova assinatura ativa
            $assinatura = Assinatura::create([
                'user_id' => $user->id,
                'status' => 'ativa',
                'data_inicio' => now(),
                'data_expiracao' => now()->addMonth(),
                'payment_id' => $paymentId,
                'valor' => $transactionAmount,
                'tentativas_cobranca' => 0,
                'status_cobranca' => 'sucesso'
            ]);

            Log::info('✅ Assinatura criada/ativada com sucesso', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'assinatura_id' => $assinatura->id,
                'payment_id' => $paymentId
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erro ao processar assinatura', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId,
                'external_reference' => $externalReference
            ]);
        }
    }

    private function handlePendingPayment($paymentDetails)
    {
        // Lógica para pagamento pendente
        Log::info('⏳ Processando pagamento pendente - Aguardando confirmação');
    }

    private function handleInProcessPayment($paymentDetails)
    {
        // Lógica para pagamento em análise
        Log::info('🔍 Processando pagamento em análise - Aguardando revisão');
    }

    private function handleRejectedPayment($paymentDetails)
    {
        $paymentId = $paymentDetails['id'] ?? null;
        $externalReference = $paymentDetails['external_reference'] ?? null;
        $statusDetail = $paymentDetails['status_detail'] ?? null;
        $reason = $this->getRejectionReason($statusDetail);
        
        Log::info('❌ Processando pagamento rejeitado', [
            'payment_id' => $paymentId,
            'external_reference' => $externalReference,
            'reason' => $reason,
            'status_detail' => $statusDetail
        ]);

        try {
            // Busca o usuário pelo external_reference (ID do usuário)
            $user = User::find($externalReference);
            
            if (!$user) {
                Log::error('❌ Usuário não encontrado para o external_reference', ['external_reference' => $externalReference]);
                return;
            }

            // Verifica se já existe assinatura para este payment_id
            $assinaturaExistente = Assinatura::where('payment_id', $paymentId)->first();
            if ($assinaturaExistente) {
                Log::info('✅ Pagamento rejeitado já processado anteriormente', [
                    'payment_id' => $paymentId,
                    'assinatura_id' => $assinaturaExistente->id
                ]);
                return;
            }

            // Busca assinatura ativa do usuário para registrar falha
            $assinaturaAtiva = Assinatura::where('user_id', $user->id)
                                       ->where('status', 'ativa')
                                       ->first();

            if ($assinaturaAtiva) {
                // Registra falha na assinatura existente
                $assinaturaAtiva->registrarFalhaCobranca($reason);
                
                Log::info('❌ Falha de cobrança registrada em assinatura existente', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'assinatura_id' => $assinaturaAtiva->id,
                    'payment_id' => $paymentId,
                    'tentativas' => $assinaturaAtiva->tentativas_cobranca,
                    'reason' => $reason
                ]);
            } else {
                // Cria registro de tentativa de pagamento rejeitada
                $assinatura = Assinatura::create([
                    'user_id' => $user->id,
                    'status' => 'cancelada',
                    'data_inicio' => now(),
                    'data_expiracao' => now(),
                    'payment_id' => $paymentId,
                    'valor' => $paymentDetails['transaction_amount'] ?? 0,
                    'tentativas_cobranca' => 1,
                    'status_cobranca' => 'falha',
                    'motivo_falha' => $reason
                ]);

                Log::info('❌ Tentativa de pagamento rejeitada registrada', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'assinatura_id' => $assinatura->id,
                    'payment_id' => $paymentId,
                    'reason' => $reason
                ]);
            }

        } catch (\Exception $e) {
            Log::error('❌ Erro ao processar pagamento rejeitado', [
                'error' => $e->getMessage(),
                'payment_id' => $paymentId,
                'external_reference' => $externalReference
            ]);
        }
    }

    private function handleCancelledPayment($paymentDetails)
    {
        // Lógica para pagamento cancelado
        Log::info('🚫 Processando pagamento cancelado');
    }

    private function handleRefundedPayment($paymentDetails)
    {
        // Lógica para pagamento reembolsado
        Log::info('💰 Processando pagamento reembolsado');
    }

    private function handleAuthorizedSubscription($subscriptionDetails)
    {
        $subscriptionId = $subscriptionDetails['id'] ?? null;
        $externalReference = $subscriptionDetails['external_reference'] ?? null;
        $payerEmail = $subscriptionDetails['payer_email'] ?? null;
        $autoRecurring = $subscriptionDetails['auto_recurring'] ?? null;
        
        Log::info('🎉 Processando assinatura recorrente autorizada', [
            'subscription_id' => $subscriptionId,
            'external_reference' => $externalReference,
            'payer_email' => $payerEmail
        ]);

        try {
            // Busca o usuário pelo external_reference (ID do usuário)
            $user = User::find($externalReference);
            
            if (!$user) {
                Log::error('❌ Usuário não encontrado para o external_reference', ['external_reference' => $externalReference]);
                return;
            }

            // Verifica se já existe assinatura para este subscription_id
            $assinaturaExistente = Assinatura::where('payment_id', $subscriptionId)->first();
            if ($assinaturaExistente) {
                Log::info('✅ Assinatura recorrente já processada anteriormente', [
                    'subscription_id' => $subscriptionId,
                    'assinatura_id' => $assinaturaExistente->id
                ]);
                return;
            }

            // Cancela assinaturas anteriores do usuário
            Assinatura::where('user_id', $user->id)
                     ->where('status', 'ativa')
                     ->update(['status' => 'cancelada']);

            // Cria nova assinatura recorrente ativa
            $assinatura = Assinatura::create([
                'user_id' => $user->id,
                'status' => 'ativa',
                'data_inicio' => now(),
                'data_expiracao' => now()->addMonth(),
                'payment_id' => $subscriptionId,
                'valor' => $autoRecurring['transaction_amount'] ?? 39.90
            ]);

            Log::info('✅ Assinatura recorrente criada/ativada com sucesso', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'assinatura_id' => $assinatura->id,
                'subscription_id' => $subscriptionId
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erro ao processar assinatura recorrente', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscriptionId,
                'external_reference' => $externalReference
            ]);
        }
    }

    private function handlePendingSubscription($subscriptionDetails)
    {
        Log::info('⏳ Processando assinatura recorrente pendente - Aguardando confirmação');
    }

    private function handleCancelledSubscription($subscriptionDetails)
    {
        $subscriptionId = $subscriptionDetails['id'] ?? null;
        $externalReference = $subscriptionDetails['external_reference'] ?? null;
        
        Log::info('🚫 Processando assinatura recorrente cancelada', [
            'subscription_id' => $subscriptionId,
            'external_reference' => $externalReference
        ]);

        try {
            // Busca o usuário pelo external_reference
            $user = User::find($externalReference);
            
            if (!$user) {
                Log::error('❌ Usuário não encontrado para o external_reference', ['external_reference' => $externalReference]);
                return;
            }

            // Cancela a assinatura local
            $assinatura = Assinatura::where('user_id', $user->id)
                                  ->where('status', 'ativa')
                                  ->first();

            if ($assinatura) {
                $assinatura->cancelar();
                Log::info('✅ Assinatura local cancelada com sucesso', [
                    'user_id' => $user->id,
                    'assinatura_id' => $assinatura->id
                ]);
            }

        } catch (\Exception $e) {
            Log::error('❌ Erro ao cancelar assinatura local', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscriptionId
            ]);
        }
    }

    private function handlePausedSubscription($subscriptionDetails)
    {
        Log::info('⏸️ Processando assinatura recorrente pausada');
    }
} 