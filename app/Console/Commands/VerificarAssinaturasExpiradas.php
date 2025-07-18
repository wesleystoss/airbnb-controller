<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Assinatura;
use Illuminate\Support\Facades\Log;

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
    protected $description = 'Verifica e atualiza assinaturas expiradas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando assinaturas expiradas...');

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

        $this->info('✅ Processo concluído!');
    }
}
