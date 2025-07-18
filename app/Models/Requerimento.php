<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requerimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'assinatura_id',
        'motivo',
        'status',
        'valor_estorno',
        'estorno_realizado',
    ];

    public function assinatura()
    {
        return $this->belongsTo(Assinatura::class);
    }
} 