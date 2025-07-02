<?php
// ARQUIVO: app/Models/FormulaRacao.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulaRacao extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'formulas_racoes';

    protected $guarded = [];

    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }

    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'formula_ingredientes')
            ->withPivot('percentual_inclusao');
    }

    public function categorias()
    {
        return $this->hasMany(CategoriaAnimal::class);
    }
}
