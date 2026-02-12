<?php
// ARQUIVO: app/Livewire/GerenciarEspecies.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Especie;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GerenciarEspecies extends Component
{
    use WithPagination;

    // Propriedades do formulário
    public $especieId;
    public $nome;
    public $modalAberto = false;

    // Propriedades para o modal de exclusão
    public $modalDelecaoAberto = false;
    public $especieParaDeletar;
    public $search = '';
    protected function rules()
    {
        return [
            'nome' => ['required', 'string', 'min:3', Rule::unique('especies')->ignore($this->especieId)],
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
        $this->reset(['especieId', 'nome']);
    }

    public function salvar()
    {
        $this->validate();
        Especie::updateOrCreate(['id' => $this->especieId], ['nome' => $this->nome]);
        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => $this->especieId ? 'Espécie atualizada com sucesso!' : 'Espécie cadastrada com sucesso!'
        ]);
        $this->fecharModal();
    }

    public function editar($id)
    {
        $especie = Especie::findOrFail($id);
        $this->especieId = $id;
        $this->nome = $especie->nome;
        $this->modalAberto = true;
    }

    /**
     * Abre o modal de confirmação de exclusão.
     */
    public function confirmarDelecao($especieId)
    {
        $this->especieParaDeletar = $especieId;
        $this->modalDelecaoAberto = true;
    }

    /**
     * Deleta a espécie após a confirmação.
     */
    public function deletar()
    {
        if (!$this->especieParaDeletar) {
            return;
        }

        $especie = Especie::withCount('animais')->find($this->especieParaDeletar);

        if ($especie && $especie->animais_count > 0) {
            $this->dispatch('toast-notification', [
                'type' => 'error',
                'message' => 'Não é possível remover a espécie, pois existem animais cadastrados nela.'
            ]);
            $this->modalDelecaoAberto = false;
            return;
        }

        if ($especie) {
            $especie->delete();
            $this->dispatch('toast-notification', [
                'type' => 'sucess',
                'message' => 'Espécie removida com sucesso!'
            ]);
        }

        $this->modalDelecaoAberto = false;
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {

        $especies = Especie::where('nome', 'like', '%' . $this->search . '%')
            ->orderBy('nome')
            ->paginate(10);

        return view('livewire.gerenciar-especies', [
            'especies' => $especies,
        ])->layout('layouts.app');
    }
}
