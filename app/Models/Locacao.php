<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    protected $fillable = [
        'nome', 'valor_total', 'data_inicio', 'data_fim', 'imovel_id', 'data_pagamento'
    ];

    public function despesas()
    {
        return $this->hasMany(Despesa::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'locacao_user')->withPivot('papel')->withTimestamps();
    }

    public function imovel()
    {
        return $this->belongsTo(Imovel::class);
    }
}
