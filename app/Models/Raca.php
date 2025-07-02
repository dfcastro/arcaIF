<?php
// ARQUIVO: app/Models/Raca.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raca extends Model
{
    use HasFactory;

    protected $table = 'racas';

    protected $fillable = [
        'nome',
        'especie_id',
    ];

    /**
     * Uma Raça pertence a uma Espécie.
     */
    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }

    /**
     * UMA RAÇA PODE TER VÁRIOS ANIMAIS.
     * ADICIONE ESTE NOVO MÉTODO.
     */
    public function animais()
    {
        return $this->hasMany(Animal::class);
    }
}