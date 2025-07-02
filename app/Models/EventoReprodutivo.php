<?php
// ARQUIVO: app/Models/EventoReprodutivo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoReprodutivo extends Model
{
    use HasFactory;

    protected $table = 'eventos_reprodutivos';
    protected $guarded = []; // Permite o preenchimento de todos os campos

    // Converte a coluna de data para um objeto Carbon automaticamente
    protected $casts = [
        'data' => 'date',
    ];

    /**
     * Um evento reprodutivo pertence a um animal (a fêmea).
     */
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    /**
     * Um evento pode ter um animal relacionado (o macho).
     */
    public function animalRelacionado()
    {
        return $this->belongsTo(Animal::class, 'animal_relacionado_id');
    }
}