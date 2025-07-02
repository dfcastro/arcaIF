<?php
// ARQUIVO: app/Models/CategoriaAnimal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaAnimal extends Model
{
    use HasFactory;
    
    protected $table = 'categorias_animais';
    protected $guarded = [];

    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }

    public function formulaRacao()
    {
        return $this->belongsTo(FormulaRacao::class);
    }

    public function animais()
    {
        return $this->hasMany(Animal::class);
    }
}