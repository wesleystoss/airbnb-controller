<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imovel extends Model
{
    protected $fillable = [
        'user_id', 'nome'
    ];

    protected $table = 'imoveis';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
