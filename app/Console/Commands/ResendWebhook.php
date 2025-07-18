<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResendWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:resend {payment_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reenvia webhook para um pagamento específico do Mercado Pago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paymentId = $this->argument('payment_id');
        
        $this->info("Reenviando webhook para o pagamento: {$paymentId}");
        
        try {
            // Simula o payload do webhook
            $webhookPayload = [
                'action' => 'payment.updated',
                'api_version' => 'v1',
                'data' => [
                    'id' => $paymentId
                ],
                'date_created' => now()->toISOString(),
                'id' => time(), // ID único para este reenvio
                'live_mode' => true,
                'type' => 'payment',
                'user_id' => '386615503'
            ];
            
            // Envia para o webhook local
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'User-Agent' => 'MercadoPago-Webhook-Resend/1.0'
            ])->post('https://airbnb.wesleystoss.com.br/api/webhook/mercadopago', $webhookPayload);
            
            if ($response->successful()) {
                $this->info('✅ Webhook reenviado com sucesso!');
                $this->info('Status: ' . $response->status());
                $this->info('Resposta: ' . $response->body());
            } else {
                $this->error('❌ Erro ao reenviar webhook');
                $this->error('Status: ' . $response->status());
                $this->error('Resposta: ' . $response->body());
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Exceção: ' . $e->getMessage());
        }
    }
}
