<?php
// ARQUIVO: app/Livewire/ListarFormulas.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormulaRacao;
use Livewire\WithPagination;

class ListarFormulas extends Component
{
    use WithPagination;

    public $modalDelecaoAberto = false;
    public $formulaParaDeletar;

    public function confirmarDelecao($id)
    {
        $this->formulaParaDeletar = $id;
        $this->modalDelecaoAberto = true;
    }

    public function deletar()
    {
        // Ao deletar uma fórmula, os animais que a usavam ficarão sem fórmula (null)
        // devido ao onDelete('set null') que definimos na migration.
        FormulaRacao::find($this->formulaParaDeletar)->delete();
        session()->flash('sucesso', 'Fórmula removida com sucesso!');
        $this->modalDelecaoAberto = false;
    }

    public function render()
    {
        $formulas = FormulaRacao::with('especie', 'ingredientes')->paginate(10);

        // Calcula o custo por kg para cada fórmula
        foreach ($formulas as $formula) {
            $custoTotal = 0;
            foreach ($formula->ingredientes as $ingrediente) {
                $percentual = $ingrediente->pivot->percentual_inclusao / 100;
                $custoTotal += $ingrediente->preco_por_kg * $percentual;
            }
            $formula->custo_por_kg = $custoTotal;
        }

        return view('livewire.listar-formulas', [
            'formulas' => $formulas,
        ])->layout('layouts.app');
    }
}