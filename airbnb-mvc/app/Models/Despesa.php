<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    protected $fillable = [
        'locacao_id', 'descricao', 'valor', 'data'
    ];

    public function locacao()
    {
        return $this->belongsTo(Locacao::class);
    }
}
