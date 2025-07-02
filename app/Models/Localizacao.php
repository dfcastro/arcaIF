<?php
// ARQUIVO: app/Models/Localizacao.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localizacao extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'localizacoes';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'descricao',
    ];

    /**
     * Define a relação de que uma localização pode ter muitos animais.
     */
    public function animais()
    {
        return $this->hasMany(Animal::class);
    }
}