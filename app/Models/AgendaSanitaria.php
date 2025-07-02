<?php
// ARQUIVO: app/Models/AgendaSanitaria.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaSanitaria extends Model
{
    use HasFactory;

    protected $table = 'agenda_sanitaria';
    protected $guarded = [];
    
    // Converte as colunas de data para objetos Carbon automaticamente
    protected $casts = [
        'data_agendada' => 'date',
        'data_conclusao' => 'date',
    ];

    /**
     * Um evento da agenda pertence a um animal.
     */
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    /**
     * Um evento da agenda está ligado a uma etapa de um protocolo.
     */
    public function protocoloEvento()
    {
        return $this->belongsTo(ProtocoloEvento::class);
    }
}