<?php
// ARQUIVO: app/Models/Raca.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raca extends Model
{
    use HasFactory;

    protected $table = 'racas'; // Informa o nome correto da tabela

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
}
