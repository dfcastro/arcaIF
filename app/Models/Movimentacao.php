<?php
// ARQUIVO: app/Models/Movimentacao.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    use HasFactory;

    protected $table = 'movimentacoes';

    protected $fillable = [
        'animal_id',
        'data',
        'tipo',
        'descricao',
        'valor',
    ];

    /**
     * Uma movimentação pertence a um animal.
     */
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}