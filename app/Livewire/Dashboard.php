<?php
// ARQUIVO: app/Livewire/Dashboard.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Animal;
use App\Models\Especie;
use App\Models\Raca;
use Livewire\Attributes\On; // Importar o atributo On

class Dashboard extends Component
{
    public $totalAnimaisAtivos;
    public $totalEspecies;
    public $totalRacas;
    public $animaisPorEspecie;
    public $chartData; // Nova propriedade para os dados do gráfico

    /**
     * O método mount é executado quando o componente é inicializado.
     */
    public function mount()
    {
        $this->carregarDados();
    }

    /**
     * Adiciona um listener para o evento 'animal-updated'.
     * Quando um animal for criado/editado/removido, este método será chamado para atualizar o dashboard.
     */
    #[On('animal-updated')]
    public function carregarDados()
    {
        $this->totalAnimaisAtivos = Animal::where('status', 'Ativo')->count();
        $this->totalEspecies = Especie::count();
        $this->totalRacas = Raca::count();
        
        $this->animaisPorEspecie = Especie::withCount(['animais' => function ($query) {
            $query->where('status', 'Ativo');
        }])->get();

        // Prepara os dados para o gráfico
        $labels = $this->animaisPorEspecie->pluck('nome');
        $data = $this->animaisPorEspecie->pluck('animais_count');

        $this->chartData = [
            'labels' => $labels,
            'data' => $data,
        ];

        // Envia um evento para o browser para atualizar o gráfico
        $this->dispatch('update-chart', data: $this->chartData);
    }


    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app');
    }
}
