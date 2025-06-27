<?php
// ARQUIVO: app/Models/Lote.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';

    protected $fillable = [
        'nome',
        'descricao',
    ];

    /**
     * A relação de muitos-para-muitos com Animal.
     * Um lote pode ter muitos animais.
     */
    public function animais()
    {
        return $this->belongsToMany(Animal::class, 'animal_lote');
    }
}
