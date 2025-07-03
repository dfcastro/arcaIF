<?php
// ARQUIVO: app/Models/Animal.php

namespace App\Models;

use Carbon\Carbon; // 1. ADICIONE ESTA LINHA NO TOPO DO FICHEIRO
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;
    protected $table = 'animais';

    // ADICIONE 'pai_id' e 'mae_id' à lista
    protected $fillable = [
        'especie_id',
        'raca_id',
        'localizacao_id',
        'categoria_animal_id',
        'pai_id', // <-- ADICIONADO
        'mae_id', // <-- ADICIONADO
        'identificacao',
        'data_nascimento',
        'sexo',
        'status',
        'observacoes',
    ];

    /**
     * NOVO ACESSOR PARA FORMATAR A IDADE
     * Este método calcula a idade em anos e meses.
     */
    public function getFormattedAgeAttribute(): string
    {
        if (!$this->data_nascimento) {
            return 'Idade não informada';
        }

        $age = Carbon::parse($this->data_nascimento)->diff(Carbon::now());

        $parts = [];
        if ($age->y > 0) {
            $parts[] = $age->y . ' ' . \Illuminate\Support\Str::plural('ano', $age->y);
        }
        if ($age->m > 0) {
            $parts[] = $age->m . ' ' . \Illuminate\Support\Str::plural('mês', $age->m);
        }
        if (empty($parts)) {
            if ($age->d > 0) {
                return $age->d . ' ' . \Illuminate\Support\Str::plural('dia', $age->d);
            }
            return 'Recém-nascido';
        }

        return implode(' e ', $parts);
    }

    // ... (suas relações existentes como especie(), raca(), etc., continuam aqui) ...
    public function especie() { return $this->belongsTo(Especie::class); }
    public function raca() { return $this->belongsTo(Raca::class); }
    public function localizacao() { return $this->belongsTo(Localizacao::class); }
    public function categoria() { return $this->belongsTo(CategoriaAnimal::class, 'categoria_animal_id'); }
    public function lotes() { return $this->belongsToMany(Lote::class, 'animal_lote'); }
    public function movimentacoes() { return $this->hasMany(Movimentacao::class)->orderBy('data', 'desc'); }
    public function agendaSanitaria() { return $this->hasMany(AgendaSanitaria::class); }

    // >> ADICIONE ESTAS NOVAS RELAÇÕES REPRODUTIVAS <<

    /**
     * Um animal pode ter um pai.
     */
    public function pai()
    {
        return $this->belongsTo(Animal::class, 'pai_id');
    }

    /**
     * Um animal pode ter uma mãe.
     */
    public function mae()
    {
        return $this->belongsTo(Animal::class, 'mae_id');
    }

    /**
     * Um animal (como pai) pode ter muitos descendentes.
     */
    public function descendentesComoPai()
    {
        return $this->hasMany(Animal::class, 'pai_id');
    }

    /**
     * Um animal (como mãe) pode ter muitos descendentes.
     */
    public function descendentesComoMae()
    {
        return $this->hasMany(Animal::class, 'mae_id');
    }

    /**
     * Um animal (fêmea) pode ter muitos eventos reprodutivos no seu histórico.
     */
    public function eventosReprodutivos()
    {
        return $this->hasMany(EventoReprodutivo::class)->orderBy('data', 'desc');
    }
}