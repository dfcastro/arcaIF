<?php
// ARQUIVO: app/Models/Especie.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    /**
     * Uma Espécie pode ter várias Raças.
     */
    public function racas()
    {
        return $this->hasMany(Raca::class);
    }

    /**
     * Uma Espécie pode ter vários Animais.
     * ADICIONE ESTE NOVO MÉTODO
     */
    public function animais()
    {
        return $this->hasMany(Animal::class);
    }
}