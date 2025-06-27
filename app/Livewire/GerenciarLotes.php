<?php
// ARQUIVO: app/Livewire/GerenciarLotes.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lote;
use Livewire\WithPagination;

class GerenciarLotes extends Component
{
    use WithPagination;

    // Propriedades do formulário
    public $loteId;
    public $nome;
    public $descricao;

    public $modalAberto = false;

    protected function rules()
    {
        return [
            'nome' => 'required|string|min:3|unique:lotes,nome,' . $this->loteId,
            'descricao' => 'nullable|string',
        ];
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

    private function resetInput()
    {
        $this->reset(['loteId', 'nome', 'descricao']);
        $this->resetErrorBag();
    }

    public function salvar()
    {
        $this->validate();

        Lote::updateOrCreate(['id' => $this->loteId], [
            'nome' => $this->nome,
            'descricao' => $this->descricao,
        ]);

        session()->flash('sucesso', $this->loteId ? 'Lote atualizado com sucesso!' : 'Lote cadastrado com sucesso!');

        $this->fecharModal();
    }

    public function editar($id)
    {
        $lote = Lote::findOrFail($id);
        $this->loteId = $id;
        $this->nome = $lote->nome;
        $this->descricao = $lote->descricao;
        $this->modalAberto = true;
    }

    public function deletar($id)
    {
        $lote = Lote::withCount('animais')->find($id);

        if ($lote->animais_count > 0) {
            session()->flash('erro', 'Não é possível remover este lote, pois existem animais associados a ele.');
            return;
        }

        $lote->delete();
        session()->flash('sucesso', 'Lote removido com sucesso!');
    }


    public function render()
    {
        return view('livewire.gerenciar-lotes', [
            'lotes' => Lote::withCount('animais')->orderBy('nome')->paginate(10),
        ])->layout('layouts.app');
    }
}
