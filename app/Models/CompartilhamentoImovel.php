<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompartilhamentoImovel extends Model
{
    protected $table = 'compartilhamentos_imoveis';
    protected $fillable = [
        'imovel_id', 'user_imovel_id', 'user_compartilhado_id'
    ];

    public function imovel()
    {
        return $this->belongsTo(Imovel::class, 'imovel_id');
    }

    public function usuarioDono()
    {
        return $this->belongsTo(User::class, 'user_imovel_id');
    }

    public function usuarioCompartilhado()
    {
        return $this->belongsTo(User::class, 'user_compartilhado_id');
    }
} 