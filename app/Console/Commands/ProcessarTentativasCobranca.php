<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Assinatura;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ProcessarTentativasCobranca extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assinaturas:processar-tentativas-cobranca';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa tentativas de cobrança para assinaturas com falhas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Processando tentativas de cobrança...');

        // Busca assinaturas que precisam de nova tentativa
        $assinaturas = Assinatura::comTentativasPendentes();

        if ($assinaturas->isEmpty()) {
            $this->info('✅ Nenhuma assinatura precisa de nova tentativa de cobrança.');
            return;
        }

        $this->info("🔄 Encontradas {$assinaturas->count()} assinatura(s) para nova tentativa.");

        foreach ($assinaturas as $assinatura) {
            $this->info("🔄 Tentativa " . ($assinatura->tentativas_cobranca + 1) . "/5 para assinatura ID: {$assinatura->id} - Usuário: {$assinatura->user->email}");
            
            try {
                $this->tentarCobranca($assinatura);
            } catch (\Exception $e) {
                Log::error('❌ Erro ao processar tentativa de cobrança', [
                    'assinatura_id' => $assinatura->id,
                    'error' => $e->getMessage()
                ]);
                
                $this->error("❌ Erro na assinatura ID: {$assinatura->id} - {$e->getMessage()}");
            }
        }

        $this->info('✅ Processo de tentativas concluído!');
    }

    private function tentarCobranca($assinatura)
    {
        $accessToken = config('services.mercadopago.access_token');
        
        // Para assinaturas recorrentes, verifica o status no Mercado Pago
        if (is_numeric($assinatura->payment_id)) {
            $this->tentarCobrancaRecorrente($assinatura, $accessToken);
        } else {
            // Para assinaturas únicas, simula uma tentativa
            $this->tentarCobrancaUnica($assinatura);
        }
    }

    private function tentarCobrancaRecorrente($assinatura, $accessToken)
    {
        // Busca detalhes da assinatura no Mercado Pago
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->get("https://api.mercadopago.com/preapproval/{$assinatura->payment_id}");

        if (!$response->successful()) {
            $assinatura->registrarFalhaCobranca('Erro ao consultar assinatura no Mercado Pago');
            $this->warn("⚠️ Erro ao consultar assinatura {$assinatura->id} no Mercado Pago");
            return;
        }

        $subscriptionDetails = $response->json();
        
        // Verifica se a assinatura ainda está ativa
        if ($subscriptionDetails['status'] === 'authorized') {
            // Assinatura ativa - tenta processar pagamento
            $this->processarPagamentoRecorrente($assinatura, $accessToken);
        } else {
            // Assinatura cancelada ou pausada
            $assinatura->registrarFalhaCobranca("Assinatura com status: {$subscriptionDetails['status']}");
            $this->warn("⚠️ Assinatura {$assinatura->id} com status: {$subscriptionDetails['status']}");
        }
    }

    private function processarPagamentoRecorrente($assinatura, $accessToken)
    {
        // Cria um pagamento recorrente
        $payment = [
            'transaction_amount' => $assinatura->valor,
            'token' => null, // Para assinaturas recorrentes, não precisa de token
            'installments' => 1,
            'payment_method_id' => 'pix', // Método padrão
            'payer' => [
                'email' => $assinatura->user->email
            ],
            'external_reference' => $assinatura->user->id,
            'description' => "Cobrança recorrente - Assinatura Airbnb Controle (Tentativa " . ($assinatura->tentativas_cobranca + 1) . ")"
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post('https://api.mercadopago.com/v1/payments', $payment);

        if ($response->successful()) {
            $paymentDetails = $response->json();
            
            if ($paymentDetails['status'] === 'approved') {
                $assinatura->registrarSucessoCobranca();
                $this->info("✅ Cobrança aprovada para assinatura {$assinatura->id}");
                
                Log::info('✅ Cobrança recorrente aprovada', [
                    'assinatura_id' => $assinatura->id,
                    'user_id' => $assinatura->user_id,
                    'payment_id' => $paymentDetails['id'],
                    'tentativa' => $assinatura->tentativas_cobranca + 1
                ]);
            } else {
                $assinatura->registrarFalhaCobranca("Pagamento com status: {$paymentDetails['status']}");
                $this->warn("⚠️ Pagamento rejeitado para assinatura {$assinatura->id} - Status: {$paymentDetails['status']}");
            }
        } else {
            $assinatura->registrarFalhaCobranca('Erro ao processar pagamento no Mercado Pago');
            $this->warn("⚠️ Erro ao processar pagamento para assinatura {$assinatura->id}");
        }
    }

    private function tentarCobrancaUnica($assinatura)
    {
        // Para assinaturas únicas, simula uma tentativa
        // Em produção, você pode implementar lógica específica
        
        $assinatura->registrarFalhaCobranca('Assinatura única - requer renovação manual');
        $this->warn("⚠️ Assinatura única {$assinatura->id} requer renovação manual");
    }
}
