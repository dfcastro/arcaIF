<?php
// ARQUIVO: app/Livewire/Dashboard.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Animal;
use App\Models\Especie;
use App\Models\Raca;

class Dashboard extends Component
{
    public $totalAnimaisAtivos;
    public $totalEspecies;
    public $totalRacas;
    public $animaisPorEspecie;

    /**
     * O método mount é executado quando o componente é inicializado.
     * É um bom lugar para carregar os dados que não mudam com frequência.
     */
    public function mount()
    {
        $this->totalAnimaisAtivos = Animal::where('status', 'Ativo')->count();
        $this->totalEspecies = Especie::count();
        $this->totalRacas = Raca::count();
        
        // Carrega as espécies junto com a contagem de animais ativos para cada uma
        $this->animaisPorEspecie = Especie::withCount(['animais' => function ($query) {
            $query->where('status', 'Ativo');
        }])->get();
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app');
    }
}
