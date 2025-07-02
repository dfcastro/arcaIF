<?php
// ARQUIVO: app/Livewire/Dashboard.php

namespace App\Livewire;

use App\Models\Animal;
use App\Models\Especie;
use App\Models\Raca;
use App\Models\CategoriaAnimal;
use App\Models\AgendaSanitaria;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    // Resumos gerais
    public $totalAnimaisAtivos;
    public $totalEspecies;
    public $totalRacas;

    // Para a Agenda Sanitária
    // >> CORREÇÃO: Inicializadas como arrays vazios para evitar o erro.
    public $eventosAtrasados = [];
    public $eventosHoje = [];

    // Para o Resumo Financeiro
    public $custoDiarioRacao = 0;
    public $consumoDiarioRacao = 0;
    
    // Para os gráficos
    public $chartDataCategorias = [];
    public $chartDataEspecies = [];

    public function mount()
    {
        $this->totalAnimaisAtivos = Animal::where('status', 'Ativo')->count();
        $this->totalEspecies = Especie::count();
        $this->totalRacas = Raca::count();
        
        $this->calcularResumoSanitario();
        $this->calcularResumoNutricional();
        $this->prepararGraficoCategorias();
        $this->prepararGraficoEspecies();
    }
    
    public function calcularResumoSanitario()
    {
        $hoje = Carbon::today();
        $eventosAgendados = AgendaSanitaria::where('status', 'Agendado')
            ->where('data_agendada', '<=', $hoje)
            ->with(['animal', 'protocoloEvento'])
            ->orderBy('data_agendada', 'asc')
            ->get();

        $this->eventosAtrasados = $eventosAgendados->filter(fn($e) => $e->data_agendada->isPast() && !$e->data_agendada->isToday());
        $this->eventosHoje = $eventosAgendados->filter(fn($e) => $e->data_agendada->isToday());
    }

    public function calcularResumoNutricional()
    {
        // ... (código existente, sem alterações)
        $categorias = CategoriaAnimal::with(['animais', 'formulaRacao.ingredientes'])->get();
        foreach ($categorias as $categoria) {
            $numeroAnimais = $categoria->animais->count();
            if ($numeroAnimais == 0) continue;
            $custoFormulaKg = 0;
            if ($categoria->formulaRacao) {
                foreach ($categoria->formulaRacao->ingredientes as $ingrediente) {
                    $percentual = $ingrediente->pivot->percentual_inclusao / 100;
                    $custoFormulaKg += $ingrediente->preco_por_kg * $percentual;
                }
            }
            $consumoTotalDiaCategoria = $numeroAnimais * $categoria->consumo_diario_kg;
            $this->consumoDiarioRacao += $consumoTotalDiaCategoria;
            $this->custoDiarioRacao += $consumoTotalDiaCategoria * $custoFormulaKg;
        }
    }

    public function prepararGraficoCategorias()
    {
        // ... (código existente, sem alterações)
        $dados = CategoriaAnimal::withCount('animais')->get();
        $labels = $dados->pluck('nome');
        $data = $dados->pluck('animais_count');
        $this->chartDataCategorias = ['labels' => $labels, 'data' => $data];
    }
    
    public function prepararGraficoEspecies()
    {
        // ... (código existente, sem alterações)
        $dados = Especie::withCount(['animais' => function ($query) {
                $query->where('status', 'Ativo');
            }])
            ->having('animais_count', '>', 0)
            ->get();
        $labels = $dados->pluck('nome');
        $data = $dados->pluck('animais_count');
        $this->chartDataEspecies = ['labels' => $labels, 'data' => $data];
    }

    public function render()
    {
        return view('livewire.dashboard-refinado')->layout('layouts.app');
    }
}