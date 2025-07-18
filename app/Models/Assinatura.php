<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'valor'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_expiracao' => 'date',
        'valor' => 'decimal:2'
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
}
