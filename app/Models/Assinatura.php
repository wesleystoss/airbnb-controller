<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Assinatura extends Model
{
    use HasFactory;

    protected $table = 'assinaturas';

    protected $fillable = [
        'user_id',
        'status',
        'data_inicio',
        'data_expiracao',
        'payment_id',
        'valor',
        'tentativas_cobranca',
        'ultima_tentativa_cobranca',
        'proxima_tentativa_cobranca',
        'status_cobranca',
        'motivo_falha'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_expiracao' => 'date',
        'valor' => 'decimal:2',
        'ultima_tentativa_cobranca' => 'datetime',
        'proxima_tentativa_cobranca' => 'datetime'
    ];

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Verifica se a assinatura está ativa
    public function isAtiva()
    {
        return $this->status === 'ativa' && $this->data_expiracao->isFuture();
    }

    // Verifica se a assinatura está expirada
    public function isExpirada()
    {
        return $this->data_expiracao->isPast();
    }

    // Ativa a assinatura
    public function ativar()
    {
        $this->update([
            'status' => 'ativa',
            'data_inicio' => now(),
            'data_expiracao' => now()->addMonth()
        ]);
    }

    // Cancela a assinatura
    public function cancelar()
    {
        $this->update(['status' => 'cancelada']);
    }

    // Expira a assinatura
    public function expirar()
    {
        $this->update(['status' => 'expirada']);
    }

    // Renova a assinatura
    public function renovar()
    {
        $this->update([
            'status' => 'ativa',
            'data_inicio' => now(),
            'data_expiracao' => now()->addMonth()
        ]);
    }

    // Busca assinatura ativa do usuário
    public static function ativaDoUsuario($userId)
    {
        return static::where('user_id', $userId)
                    ->where('status', 'ativa')
                    ->where('data_expiracao', '>', now())
                    ->first();
    }

    // Busca assinatura por payment_id
    public static function porPaymentId($paymentId)
    {
        return static::where('payment_id', $paymentId)->first();
    }

    // Registra uma tentativa de cobrança falhada
    public function registrarFalhaCobranca($motivo = null)
    {
        $this->increment('tentativas_cobranca');
        
        $this->update([
            'ultima_tentativa_cobranca' => now(),
            'proxima_tentativa_cobranca' => now()->addDay(), // Próxima tentativa amanhã
            'status_cobranca' => 'falha',
            'motivo_falha' => $motivo
        ]);

        // Se chegou a 5 tentativas, cancela a assinatura
        if ($this->tentativas_cobranca >= 5) {
            $this->cancelarPorFalhas();
        }
    }

    // Registra sucesso na cobrança
    public function registrarSucessoCobranca()
    {
        $this->update([
            'tentativas_cobranca' => 0,
            'ultima_tentativa_cobranca' => now(),
            'proxima_tentativa_cobranca' => null,
            'status_cobranca' => 'sucesso',
            'motivo_falha' => null
        ]);
    }

    // Cancela assinatura por múltiplas falhas
    public function cancelarPorFalhas()
    {
        $this->update([
            'status' => 'cancelada',
            'status_cobranca' => 'falha',
            'motivo_falha' => 'Cancelada automaticamente após 5 tentativas de cobrança falhadas'
        ]);

        Log::info('🚫 Assinatura cancelada por múltiplas falhas de cobrança', [
            'assinatura_id' => $this->id,
            'user_id' => $this->user_id,
            'user_email' => $this->user->email,
            'tentativas' => $this->tentativas_cobranca
        ]);
    }

    // Verifica se deve tentar cobrar novamente
    public function deveTentarCobrar()
    {
        return $this->status === 'ativa' && 
               $this->tentativas_cobranca < 5 && 
               $this->proxima_tentativa_cobranca && 
               $this->proxima_tentativa_cobranca->isPast();
    }

    // Busca assinaturas que precisam de nova tentativa de cobrança
    public static function comTentativasPendentes()
    {
        return static::where('status', 'ativa')
                    ->where('tentativas_cobranca', '<', 5)
                    ->where('proxima_tentativa_cobranca', '<=', now())
                    ->get();
    }

    // Busca assinaturas com falhas de cobrança
    public static function comFalhasCobranca()
    {
        return static::where('status', 'ativa')
                    ->where('status_cobranca', 'falha')
                    ->get();
    }
}
