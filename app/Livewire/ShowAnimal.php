<?php
// ARQUIVO: app/Livewire/ShowAnimal.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Animal;
use App\Models\Movimentacao;
use Livewire\Attributes\On;

class ShowAnimal extends Component
{
    public Animal $animal;

    // Formulário de ADIÇÃO
    public $data;
    public $tipo = 'Observação';
    public $descricao;
    public $valor;
    public $unidade = 'Kg';

    // Modal de EDIÇÃO
    public ?Movimentacao $movimentacaoEmEdicao = null;
    public $showEditModal = false;
    public $dataEdicao;
    public $tipoEdicao;
    public $descricaoEdicao;
    public $valorEdicao;
    public $unidadeEdicao;

    // Dados do Gráfico e Aba Ativa
    public $pesagemChartData = [];
    public $activeTab = 'Todos';


    public function mount(Animal $animal)
    {
        $this->animal = $animal->load('especie', 'raca', 'movimentacoes');
        $this->data = now()->format('Y-m-d');
        $this->prepararDadosGrafico();
    }

    /**
     * Prepara os dados para o gráfico de evolução de peso.
     */
    public function prepararDadosGrafico()
    {
        $pesagens = $this->animal->movimentacoes->where('tipo', 'Pesagem')->sortBy('data');

        // A CORREÇÃO ESTÁ AQUI: O método ->values() garante que os dados sejam um array simples.
        $labels = $pesagens->map(fn($item) => \Carbon\Carbon::parse($item->data)->format('d/m/Y'))->values();
        $data = $pesagens->map(fn($item) => floatval(preg_replace('/[^\d,.]/', '', $item->valor)))->values();

        $this->pesagemChartData = ['labels' => $labels, 'data' => $data];
        $this->dispatch('update-pesagem-chart', data: $this->pesagemChartData);
    }

    private function resetAddForm()
    {
        $this->reset(['tipo', 'descricao', 'valor', 'unidade']);
        $this->data = now()->format('Y-m-d');
        $this->unidade = 'Kg';
        $this->resetErrorBag();
    }

    public function salvarMovimentacao()
    {
        $regras = [
            'data' => 'required|date',
            'tipo' => 'required|in:Pesagem,Vacinação,Medicação,Observação,Venda,Óbito',
            'descricao' => 'required|string|min:3',
        ];

        if ($this->tipo === 'Pesagem') {
            $regras['valor'] = 'required|numeric';
            $regras['unidade'] = 'required|in:Kg,@';
        } else {
            $regras['valor'] = 'nullable|string|max:255';
        }
        
        $dadosValidados = $this->validate($regras);
        
        if ($this->tipo === 'Pesagem') {
            $dadosValidados['valor'] = $this->valor . ' ' . $this->unidade;
        }

        $this->animal->movimentacoes()->create($dadosValidados);
        
        if (in_array($this->tipo, ['Venda', 'Óbito'])) {
            $this->animal->status = $this->tipo === 'Venda' ? 'Vendido' : 'Óbito';
            $this->animal->save();
        }

        session()->flash('sucesso', 'Evento registado com sucesso no histórico!');
        $this->resetAddForm();
        $this->animal->refresh();
        $this->prepararDadosGrafico();
    }
    
    public function startEditing(Movimentacao $movimentacao)
    {
        $this->movimentacaoEmEdicao = $movimentacao;
        $this->dataEdicao = $movimentacao->data;
        $this->tipoEdicao = $movimentacao->tipo;
        $this->descricaoEdicao = $movimentacao->descricao;
        
        if ($this->tipoEdicao === 'Pesagem') {
            $partes = explode(' ', $movimentacao->valor);
            $this->valorEdicao = floatval($partes[0]) ?? null;
            $this->unidadeEdicao = $partes[1] ?? 'Kg';
        } else {
            $this->valorEdicao = $movimentacao->valor;
        }
        
        $this->showEditModal = true;
    }

    public function updateMovimentacao()
    {
        $regras = [
            'dataEdicao' => 'required|date',
            'tipoEdicao' => 'required|in:Pesagem,Vacinação,Medicação,Observação,Venda,Óbito',
            'descricaoEdicao' => 'required|string|min:3',
        ];

        if ($this->tipoEdicao === 'Pesagem') {
            $regras['valorEdicao'] = 'required|numeric';
            $regras['unidadeEdicao'] = 'required|in:Kg,@';
        } else {
            $regras['valorEdicao'] = 'nullable|string|max:255';
        }

        $this->validate($regras);
        
        $valorFinal = $this->valorEdicao;
        if ($this->tipoEdicao === 'Pesagem') {
            $valorFinal = $this->valorEdicao . ' ' . $this->unidadeEdicao;
        }
        
        $this->movimentacaoEmEdicao->update([
            'data' => $this->dataEdicao,
            'tipo' => $this->tipoEdicao,
            'descricao' => $this->descricaoEdicao,
            'valor' => $valorFinal,
        ]);

        $this->showEditModal = false;
        $this->animal->refresh();
        $this->prepararDadosGrafico();
        session()->flash('sucesso', 'Evento atualizado com sucesso!');
    }

    public function deleteMovimentacao($movimentacaoId)
    {
        Movimentacao::find($movimentacaoId)->delete();
        $this->animal->refresh();
        $this->prepararDadosGrafico();
        session()->flash('sucesso', 'Evento removido do histórico com sucesso!');
    }

    public function render()
    {
        // Lógica de filtragem movida para aqui
        $movimentacoesFiltradas = $this->activeTab == 'Todos'
            ? $this->animal->movimentacoes
            : $this->animal->movimentacoes->where('tipo', $this->activeTab);

        return view('livewire.show-animal', [
            'movimentacoesFiltradas' => $movimentacoesFiltradas
        ])->layout('layouts.app');
    }
}
