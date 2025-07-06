<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    protected $fillable = [
        'nome', 'valor_total', 'data_inicio', 'data_fim'
    ];

    public function despesas()
    {
        return $this->hasMany(Despesa::class);
    }
}
