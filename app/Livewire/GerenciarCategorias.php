<?php
// ARQUIVO: app/Livewire/GerenciarCategorias.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CategoriaAnimal;
use App\Models\Especie;
use App\Models\FormulaRacao;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GerenciarCategorias extends Component
{
    use WithPagination;

    // Propriedades do formulário
    public $categoriaId;
    public $nome;
    public $especie_id;
    public $formula_racao_id;
    public $consumo_diario_kg;
    public $descricao;

    // Listas para os dropdowns
    public $todasEspecies = [];
    public $formulasDisponiveis = [];

    // Controlo dos modais
    public $modalAberto = false;
    public $modalDelecaoAberto = false;
    public $categoriaParaDeletar;

    protected function rules()
    {
        return [
            'nome' => 'required|string|min:3',
            'especie_id' => 'required|exists:especies,id',
            'formula_racao_id' => 'required|exists:formulas_racoes,id',
            'consumo_diario_kg' => 'required|numeric|min:0',
            'descricao' => 'nullable|string',
        ];
    }

    public function mount()
    {
        $this->todasEspecies = Especie::orderBy('nome')->get();
    }
    
    // Filtra as fórmulas de ração sempre que a espécie muda
    public function updatedEspecieId($value)
    {
        if ($value) {
            $this->formulasDisponiveis = FormulaRacao::where('especie_id', $value)->orderBy('nome_formula')->get();
        } else {
            $this->formulasDisponiveis = [];
        }
        $this->reset('formula_racao_id');
    }

    private function resetInput()
    {
        $this->resetExcept('todasEspecies');
        $this->formulasDisponiveis = [];
        $this->resetErrorBag();
    }

    public function abrirModal()
    {
        $this->resetInput();
        $this->modalAberto = true;
    }

    public function fecharModal()
    {
        $this->modalAberto = false;
    }

    public function salvar()
    {
        $this->validate();

        CategoriaAnimal::updateOrCreate(['id' => $this->categoriaId], [
            'nome' => $this->nome,
            'especie_id' => $this->especie_id,
            'formula_racao_id' => $this->formula_racao_id,
            'consumo_diario_kg' => $this->consumo_diario_kg,
            'descricao' => $this->descricao,
        ]);
        session()->flash('sucesso', $this->categoriaId ? 'Categoria atualizada com sucesso!' : 'Categoria cadastrada com sucesso!');
        $this->fecharModal();
    }

    public function editar($id)
    {
        $categoria = CategoriaAnimal::findOrFail($id);
        $this->categoriaId = $id;
        $this->nome = $categoria->nome;
        $this->especie_id = $categoria->especie_id;
        
        // Carrega as fórmulas para a espécie selecionada
        $this->formulasDisponiveis = FormulaRacao::where('especie_id', $this->especie_id)->orderBy('nome_formula')->get();
        
        $this->formula_racao_id = $categoria->formula_racao_id;
        $this->consumo_diario_kg = $categoria->consumo_diario_kg;
        $this->descricao = $categoria->descricao;

        $this->modalAberto = true;
    }

    public function confirmarDelecao($id)
    {
        $this->categoriaParaDeletar = $id;
        $this->modalDelecaoAberto = true;
    }

    public function deletar()
    {
        $categoria = CategoriaAnimal::withCount('animais')->find($this->categoriaParaDeletar);

        if ($categoria && $categoria->animais_count > 0) {
            session()->flash('erro', 'Não é possível remover, pois existem animais nesta categoria.');
        } else {
            $categoria->delete();
            session()->flash('sucesso', 'Categoria removida com sucesso!');
        }

        $this->modalDelecaoAberto = false;
    }

    public function render()
    {
        return view('livewire.gerenciar-categorias', [
            'categorias' => CategoriaAnimal::with('especie', 'formulaRacao')->orderBy('nome')->paginate(10),
        ])->layout('layouts.app');
    }
}