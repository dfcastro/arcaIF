<?php
// ARQUIVO: app/Livewire/GerenciarLotes.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lote;
use Livewire\WithPagination;

class GerenciarLotes extends Component
{
    use WithPagination;

    // Propriedades do formulário de edição/criação
    public $loteId;
    public $nome;
    public $descricao;
    public $modalAberto = false;

    // Propriedades para o modal de exclusão
    public $modalDelecaoAberto = false;
    public $loteParaDeletar;

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

        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => $this->loteId ? 'Lote atualizado com sucesso!' : 'Lote cadastrado com sucesso!'
        ]);
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

    /**
     * Abre o modal de confirmação de exclusão.
     */
    public function confirmarDelecao($loteId)
    {
        $this->loteParaDeletar = $loteId;
        $this->modalDelecaoAberto = true;
    }

    /**
     * Deleta o lote após a confirmação.
     */
    public function deletar()
    {
        if (!$this->loteParaDeletar) {
            return;
        }

        $lote = Lote::withCount('animais')->find($this->loteParaDeletar);

        if ($lote && $lote->animais_count > 0) {
            $this->dispatch('toast-notification', [
                'type' => 'error',
                'message' => 'Não é possível remover este lote, pois existem animais associados a ele.'
            ]);
            $this->modalDelecaoAberto = false;
            return;
        }

        if ($lote) {
            $lote->delete();
            $this->dispatch('toast-notification', [
                'type' => 'sucess',
                'message' => 'Lote removido com sucesso!'
            ]);
        }

        $this->modalDelecaoAberto = false;
    }

    public function render()
    {
        return view('livewire.gerenciar-lotes', [
            'lotes' => Lote::withCount('animais')->orderBy('nome')->paginate(10),
        ])->layout('layouts.app');
    }
}
