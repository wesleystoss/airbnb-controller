<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imovel extends Model
{
    protected $fillable = [
        'user_id', 'nome', 'ical_url', 'last_ical_sync', 'calendar_events'
    ];

    protected $table = 'imoveis';

    protected $casts = [
        'last_ical_sync' => 'datetime',
        'calendar_events' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function compartilhamentos()
    {
        return $this->hasMany(CompartilhamentoImovel::class, 'imovel_id');
    }
}
