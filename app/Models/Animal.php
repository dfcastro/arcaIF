<?php
// ARQUIVO: app/Models/Animal.php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;
    protected $table = 'animais';

    protected $fillable = [
        'especie_id',
        'raca_id',
        'localizacao_id',
        'categoria_animal_id',
        'pai_id',
        'mae_id',
        'identificacao',
        'data_nascimento',
        'sexo',
        'status',
        'status_reprodutivo',
        'observacoes',
    ];

    // **NOVA MATRIZ DE CONFIGURAÇÃO REPRODUTIVA**
    private const REPRODUCTIVE_PARAMETERS = [
        'suíno' => [
            'gestation' => 114,
            'heat_cycle' => 21,
            'pregnancy_check' => 25,
            'weaning_to_heat' => 5, // Específico para suínos
        ],
        'bovino' => [
            'gestation' => 283,
            'heat_cycle' => 21,
            'pregnancy_check' => 35,
        ],
        'ovino' => [
            'gestation' => 150,
            'heat_cycle' => 17,
            'pregnancy_check' => 45,
        ],
        'equino' => [
            'gestation' => 340,
            'heat_cycle' => 22,
            'pregnancy_check' => 20,
        ],
        // Adicione outras espécies aqui no futuro
    ];

    public function getFormattedAgeAttribute(): string
    {
        if (!$this->data_nascimento) return 'Idade não informada';

        $age = Carbon::parse($this->data_nascimento)->diff(Carbon::now());
        $parts = [];
        if ($age->y > 0) $parts[] = $age->y . ' ' . \Illuminate\Support\Str::plural('ano', $age->y);
        if ($age->m > 0) $parts[] = $age->m . ' ' . \Illuminate\Support\Str::plural('mês', $age->m);

        if (empty($parts)) {
            return $age->d > 0 ? $age->d . ' ' . \Illuminate\Support\Str::plural('dia', $age->d) : 'Recém-nascido';
        }
        return implode(' e ', $parts);
    }

    public function getReproductiveCycleAttribute()
    {
        if ($this->sexo !== 'Fêmea' || !$this->status_reprodutivo) {
            return null;
        }

        $speciesName = strtolower($this->load('especie')->especie->nome);

        // **LÓGICA MELHORADA: Verifica se existem parâmetros para a espécie**
        if (!array_key_exists($speciesName, self::REPRODUCTIVE_PARAMETERS)) {
            return null; // Se não houver configuração, não faz nada
        }

        $params = self::REPRODUCTIVE_PARAMETERS[$speciesName];

        $cycle = new \stdClass();
        $cycle->status = $this->status_reprodutivo;
        $cycle->dates = [];
        $cycle->info = null;

        $lastMating = $this->eventosReprodutivos()
            ->whereIn('tipo', ['Cobrição', 'Inseminação'])
            ->latest('data')
            ->first();

        if ($this->status_reprodutivo === 'Gestante' && $lastMating) {
            $matingDate = Carbon::parse($lastMating->data);
            $cycle->dates['Previsão de Parto'] = $matingDate->copy()->addDays($params['gestation']);
            $cycle->dates['Diagnóstico Gestação'] = $matingDate->copy()->addDays($params['pregnancy_check']);
            $cycle->info = "Coberta em: " . $lastMating->data->format('d/m/Y');
        }

        if ($this->status_reprodutivo === 'Lactante') {
            $lastBirth = $this->eventosReprodutivos()->where('tipo', 'Parto')->latest('data')->first();
            if ($lastBirth) {
                $cycle->info = "Último parto em: " . $lastBirth->data->format('d/m/Y');
            }
        }

        // Lógica de desmame específica para suínos
        if ($speciesName === 'suíno' && $this->status_reprodutivo === 'Desmamada') {
            $lastWeaning = $this->eventosReprodutivos()->where('tipo', 'Desmame')->latest('data')->first();
            if ($lastWeaning) {
                $weaningDate = Carbon::parse($lastWeaning->data);
                $cycle->dates['Previsão de Cio'] = $weaningDate->copy()->addDays($params['weaning_to_heat']);
                $cycle->info = "Desmamada em: " . $lastWeaning->data->format('d/m/Y');
            }
        }

        return $cycle;
    }

    // --- Relações ---
    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }
    public function raca()
    {
        return $this->belongsTo(Raca::class);
    }
    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class);
    }
    public function categoria()
    {
        return $this->belongsTo(CategoriaAnimal::class, 'categoria_animal_id');
    }
    public function lotes()
    {
        return $this->belongsToMany(Lote::class, 'animal_lote');
    }
    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class)->orderBy('data', 'desc');
    }
    public function agendaSanitaria()
    {
        return $this->hasMany(AgendaSanitaria::class);
    }
    public function pai()
    {
        return $this->belongsTo(Animal::class, 'pai_id');
    }
    public function mae()
    {
        return $this->belongsTo(Animal::class, 'mae_id');
    }
    public function descendentesComoPai()
    {
        return $this->hasMany(Animal::class, 'pai_id');
    }
    public function descendentesComoMae()
    {
        return $this->hasMany(Animal::class, 'mae_id');
    }
    public function eventosReprodutivos()
    {
        return $this->hasMany(EventoReprodutivo::class)->orderBy('data', 'desc');
    }
}
