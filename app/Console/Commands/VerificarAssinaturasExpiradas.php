<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Assinatura;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class VerificarAssinaturasExpiradas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assinaturas:verificar-expiradas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica e atualiza assinaturas expiradas e renova assinaturas recorrentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando assinaturas...');

        // 1. Verifica assinaturas expiradas
        $this->verificarExpiradas();

        // 2. Renova assinaturas recorrentes
        $this->renovarRecorrentes();

        $this->info('✅ Processo concluído!');
    }

    private function verificarExpiradas()
    {
        $this->info('📅 Verificando assinaturas expiradas...');

        // Busca assinaturas ativas que expiraram
        $assinaturasExpiradas = Assinatura::where('status', 'ativa')
                                         ->where('data_expiracao', '<', now())
                                         ->get();

        if ($assinaturasExpiradas->isEmpty()) {
            $this->info('✅ Nenhuma assinatura expirada encontrada.');
            return;
        }

        $this->info("📅 Encontradas {$assinaturasExpiradas->count()} assinatura(s) expirada(s).");

        foreach ($assinaturasExpiradas as $assinatura) {
            $this->info("🔄 Atualizando assinatura ID: {$assinatura->id} - Usuário: {$assinatura->user->email}");
            
            $assinatura->update(['status' => 'expirada']);
            
            Log::info('📅 Assinatura expirada automaticamente', [
                'assinatura_id' => $assinatura->id,
                'user_id' => $assinatura->user_id,
                'user_email' => $assinatura->user->email,
                'data_expiracao' => $assinatura->data_expiracao
            ]);
        }
    }

    private function renovarRecorrentes()
    {
        $this->info('🔄 Verificando assinaturas recorrentes para renovação...');

        // Busca assinaturas ativas que expiram em até 3 dias
        $assinaturasParaRenovar = Assinatura::where('status', 'ativa')
                                           ->where('data_expiracao', '<=', now()->addDays(3))
                                           ->where('data_expiracao', '>', now())
                                           ->get();

        if ($assinaturasParaRenovar->isEmpty()) {
            $this->info('✅ Nenhuma assinatura recorrente para renovar.');
            return;
        }

        $this->info("🔄 Encontradas {$assinaturasParaRenovar->count()} assinatura(s) para renovação.");

        foreach ($assinaturasParaRenovar as $assinatura) {
            $this->info("🔄 Renovando assinatura ID: {$assinatura->id} - Usuário: {$assinatura->user->email}");
            
            try {
                // Verifica se é uma assinatura recorrente (tem payment_id que começa com números)
                if (is_numeric($assinatura->payment_id)) {
                    $this->renovarAssinaturaRecorrente($assinatura);
                } else {
                    // Assinatura única - apenas atualiza a data de expiração
                    $assinatura->update([
                        'data_expiracao' => now()->addMonth()
                    ]);
                    
                    Log::info('✅ Assinatura única renovada', [
                        'assinatura_id' => $assinatura->id,
                        'user_id' => $assinatura->user_id,
                        'nova_expiracao' => $assinatura->data_expiracao
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('❌ Erro ao renovar assinatura', [
                    'assinatura_id' => $assinatura->id,
                    'error' => $e->getMessage()
                ]);
                
                $this->error("❌ Erro ao renovar assinatura ID: {$assinatura->id} - {$e->getMessage()}");
            }
        }
    }

    private function renovarAssinaturaRecorrente($assinatura)
    {
        $accessToken = config('services.mercadopago.access_token');
        
        // Busca detalhes da assinatura no Mercado Pago
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->get("https://api.mercadopago.com/preapproval/{$assinatura->payment_id}");

        if (!$response->successful()) {
            throw new \Exception('Erro ao buscar detalhes da assinatura no Mercado Pago');
        }

        $subscriptionDetails = $response->json();
        
        // Verifica se a assinatura ainda está ativa no Mercado Pago
        if ($subscriptionDetails['status'] === 'authorized') {
            // Renova a assinatura local
            $assinatura->update([
                'data_expiracao' => now()->addMonth()
            ]);
            
            Log::info('✅ Assinatura recorrente renovada', [
                'assinatura_id' => $assinatura->id,
                'user_id' => $assinatura->user_id,
                'subscription_id' => $assinatura->payment_id,
                'nova_expiracao' => $assinatura->data_expiracao
            ]);
        } else {
            // Assinatura cancelada no Mercado Pago
            $assinatura->update(['status' => 'cancelada']);
            
            Log::info('🚫 Assinatura recorrente cancelada no Mercado Pago', [
                'assinatura_id' => $assinatura->id,
                'user_id' => $assinatura->user_id,
                'subscription_id' => $assinatura->payment_id,
                'status_mp' => $subscriptionDetails['status']
            ]);
        }
    }
}
