<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Assinatura;
use Carbon\Carbon;

class CriarAssinaturaGratuita extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assinatura:criar-gratuita 
                            {user_id : ID do usuário}
                            {--dias=30 : Duração em dias (padrão: 30)}
                            {--valor=0 : Valor da assinatura (padrão: 0.00)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria uma assinatura gratuita para um usuário específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = (int) $this->argument('user_id');
        $dias = (int) $this->option('dias');
        $valor = (float) $this->option('valor');

        // Verifica se o usuário existe
        $user = User::find($userId);
        if (!$user) {
            $this->error("❌ Usuário com ID {$userId} não encontrado!");
            return 1;
        }

        // Verifica se já tem assinatura ativa
        $assinaturaAtiva = $user->assinaturaAtiva;
        if ($assinaturaAtiva && $assinaturaAtiva->status === 'ativa') {
            $this->warn("⚠️  Usuário já possui uma assinatura ativa!");
            $this->info("Status: {$assinaturaAtiva->status}");
            $this->info("Expira em: " . Carbon::parse($assinaturaAtiva->data_expiracao)->format('d/m/Y'));
            
            if (!$this->confirm('Deseja criar uma nova assinatura mesmo assim?')) {
                $this->info('Operação cancelada.');
                return 0;
            }
        }

        // Cancela assinatura ativa se existir
        if ($assinaturaAtiva && $assinaturaAtiva->status === 'ativa') {
            $assinaturaAtiva->cancelar();
            $this->info("🔄 Assinatura anterior cancelada.");
        }

        // Cria nova assinatura gratuita
        $dataInicio = now();
        $dataExpiracao = now()->addDays($dias);

        $assinatura = Assinatura::create([
            'user_id' => $user->id,
            'status' => 'ativa',
            'data_inicio' => $dataInicio,
            'data_expiracao' => $dataExpiracao,
            'payment_id' => 'GRATUITA_' . time() . '_' . $user->id,
            'valor' => $valor,
        ]);

        $this->info("✅ Assinatura gratuita criada com sucesso!");
        $this->info("👤 Usuário: {$user->name} ({$user->email})");
        $this->info("📅 Início: " . $dataInicio->format('d/m/Y H:i:s'));
        $this->info("📅 Expiração: " . $dataExpiracao->format('d/m/Y H:i:s'));
        $this->info("💰 Valor: R$ " . number_format($valor, 2, ',', '.'));
        $this->info("🆔 ID da Assinatura: {$assinatura->id}");

        return 0;
    }
} 