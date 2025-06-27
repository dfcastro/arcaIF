<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;
    protected $table = 'animais';

    // ADICIONE 'raca_id' NA LISTA
    protected $fillable = [
        'especie_id',
        'raca_id', // <-- ADICIONADO
        'identificacao',
        'data_nascimento',
        'sexo',
        'status',
        'observacoes',
    ];

    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }

    /**
     * Um Animal pertence a uma Raça.
     * ADICIONE ESTE MÉTODO
     */
    public function raca()
    {
        return $this->belongsTo(Raca::class);
    }
}