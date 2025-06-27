<?php
// ARQUIVO: app/Livewire/ShowAnimal.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Animal;
use App\Models\Movimentacao;

class ShowAnimal extends Component
{
    public Animal $animal;

    // Propriedades para o formulário de ADIÇÃO de movimentação
    public $data;
    public $tipo = 'Observação';
    public $descricao;
    public $valor;
    
    // Propriedades para o modal de EDIÇÃO de movimentação
    public ?Movimentacao $movimentacaoEmEdicao = null;
    public $showEditModal = false;
    public $dataEdicao;
    public $tipoEdicao;
    public $descricaoEdicao;
    public $valorEdicao;

    // Propriedade para controlar a aba ativa
    public $activeTab = 'Todos';


    public function mount(Animal $animal)
    {
        $this->animal = $animal->load('especie', 'raca', 'movimentacoes');
        $this->data = now()->format('Y-m-d');
    }

    private function resetAddForm()
    {
        $this->reset(['tipo', 'descricao', 'valor']);
        $this->data = now()->format('Y-m-d');
        $this->resetErrorBag();
    }

    public function salvarMovimentacao()
    {
        $validatedData = $this->validate([
            'data' => 'required|date',
            'tipo' => 'required|in:Pesagem,Vacinação,Medicação,Observação,Venda,Óbito',
            'descricao' => 'required|string|min:3',
            'valor' => 'nullable|string|max:255',
        ]);

        $this->animal->movimentacoes()->create($validatedData);
        
        if (in_array($this->tipo, ['Venda', 'Óbito'])) {
            $this->animal->status = $this->tipo === 'Venda' ? 'Vendido' : 'Óbito';
            $this->animal->save();
        }

        session()->flash('sucesso', 'Evento registado com sucesso no histórico!');
        $this->resetAddForm();
        $this->animal->refresh();
    }
    
    public function startEditing(Movimentacao $movimentacao)
    {
        $this->movimentacaoEmEdicao = $movimentacao;
        $this->dataEdicao = $movimentacao->data;
        $this->tipoEdicao = $movimentacao->tipo;
        $this->descricaoEdicao = $movimentacao->descricao;
        $this->valorEdicao = $movimentacao->valor;
        $this->showEditModal = true;
    }

    public function updateMovimentacao()
    {
        $validatedData = $this->validate([
            'dataEdicao' => 'required|date',
            'tipoEdicao' => 'required|in:Pesagem,Vacinação,Medicação,Observação,Venda,Óbito',
            'descricaoEdicao' => 'required|string|min:3',
            'valorEdicao' => 'nullable|string|max:255',
        ]);

        $updateData = [
            'data' => $validatedData['dataEdicao'],
            'tipo' => $validatedData['tipoEdicao'],
            'descricao' => $validatedData['descricaoEdicao'],
            'valor' => $validatedData['valorEdicao'],
        ];

        $this->movimentacaoEmEdicao->update($updateData);

        $this->showEditModal = false;
        $this->animal->refresh();
        session()->flash('sucesso', 'Evento atualizado com sucesso!');
    }

    public function deleteMovimentacao($movimentacaoId)
    {
        Movimentacao::find($movimentacaoId)->delete();
        $this->animal->refresh();
        session()->flash('sucesso', 'Evento removido do histórico com sucesso!');
    }


    public function render()
    {
        // A lógica de filtragem foi movida para cá
        $movimentacoesFiltradas = $this->activeTab == 'Todos'
            ? $this->animal->movimentacoes
            : $this->animal->movimentacoes->where('tipo', $this->activeTab);

        return view('livewire.show-animal', [
            'movimentacoesFiltradas' => $movimentacoesFiltradas
        ])->layout('layouts.app');
    }
}
