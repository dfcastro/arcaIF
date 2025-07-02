<?php
// ARQUIVO: app/Livewire/Relatorios.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lote;
use App\Models\Animal;
use App\Models\Movimentacao; // Importar o model Movimentacao

class Relatorios extends Component
{
    // Propriedades para os filtros
    public $tipoRelatorio = '';
    public $loteId;
    public $animalId;
    public $data_inicio; // Novo
    public $data_fim;    // Novo

    // Propriedades para guardar os dados do relatório
    public $dadosRelatorio = [];
    public $loteSelecionado;
    public $animalSelecionado;
    
    public function mount()
    {
        $this->data_inicio = now()->subMonth()->format('Y-m-d');
        $this->data_fim = now()->format('Y-m-d');
    }

    /**
     * Esta é a correção: Este método é executado sempre que o tipoRelatorio muda.
     * Ele limpa todos os outros filtros e os dados do relatório anterior.
     */
    public function updatedTipoRelatorio()
    {
        $this->reset(['loteId', 'animalId', 'dadosRelatorio', 'loteSelecionado', 'animalSelecionado']);
        $this->resetErrorBag();
    }

    /**
     * Gera o relatório com base nos filtros selecionados.
     */
    public function gerarRelatorio()
    {
        $regras = [
            'tipoRelatorio' => 'required',
            'loteId' => 'required_if:tipoRelatorio,animais_por_lote',
            'animalId' => 'required_if:tipoRelatorio,historico_pesagem,historico_vacinacao',
            'data_inicio' => 'required_if:tipoRelatorio,vacinacao_por_periodo|date',
            'data_fim' => 'required_if:tipoRelatorio,vacinacao_por_periodo|date|after_or_equal:data_inicio',
        ];

        $this->validate($regras);
        
        // Limpa os dados anteriores
        $this->dadosRelatorio = [];
        $this->loteSelecionado = null;
        $this->animalSelecionado = null;

        if ($this->tipoRelatorio === 'animais_por_lote') {
            $this->loteSelecionado = Lote::find($this->loteId);
            $this->dadosRelatorio = Animal::whereHas('lotes', function($query) {
                $query->where('lote_id', $this->loteId);
            })->with('especie', 'raca')->get();
        }

        if ($this->tipoRelatorio === 'historico_pesagem') {
            $this->animalSelecionado = Animal::find($this->animalId);
            if ($this->animalSelecionado) {
                $this->dadosRelatorio = $this->animalSelecionado
                                            ->movimentacoes()
                                            ->where('tipo', 'Pesagem')
                                            ->orderBy('data', 'asc')
                                            ->get();
            }
        }

        if ($this->tipoRelatorio === 'historico_vacinacao') {
            $this->animalSelecionado = Animal::find($this->animalId);
            if ($this->animalSelecionado) {
                $this->dadosRelatorio = $this->animalSelecionado
                                            ->movimentacoes()
                                            ->where('tipo', 'Vacinação')
                                            ->orderBy('data', 'asc')
                                            ->get();
            }
        }
        
        // Nova lógica para o relatório de vacinação por período
        if ($this->tipoRelatorio === 'vacinacao_por_periodo') {
            $this->dadosRelatorio = Movimentacao::where('tipo', 'Vacinação')
                                        ->whereBetween('data', [$this->data_inicio, $this->data_fim])
                                        ->with('animal') // Carrega os dados do animal associado
                                        ->orderBy('data', 'desc')
                                        ->get();
        }
    }

    public function render()
    {
        return view('livewire.relatorios', [
            'lotes' => Lote::orderBy('nome')->get(),
            'animais' => Animal::where('status', 'Ativo')->orderBy('identificacao')->get()
        ])->layout('layouts.app');
    }
}
