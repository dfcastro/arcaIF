<?php
// ARQUIVO: app/Livewire/GerenciarLocalizacoes.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Localizacao;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GerenciarLocalizacoes extends Component
{
    use WithPagination;

    // Propriedades do formulário
    public $localizacaoId;
    public $nome;
    public $descricao;
    public $modalAberto = false;

    // Propriedades para o modal de exclusão
    public $modalDelecaoAberto = false;
    public $localizacaoParaDeletar;
    public $search = '';

    protected function rules()
    {
        return [
            'nome' => ['required', 'string', 'min:3', Rule::unique('localizacoes')->ignore($this->localizacaoId)],
            'descricao' => 'nullable|string|max:255',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
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
        $this->reset(['localizacaoId', 'nome', 'descricao']);
        $this->resetErrorBag();
    }

    public function salvar()
    {
        $this->validate();
        Localizacao::updateOrCreate(['id' => $this->localizacaoId], [
            'nome' => $this->nome,
            'descricao' => $this->descricao,
        ]);
        $this->dispatch('toast-notification', [
            'type' => 'success',
            'message' => $this->localizacaoId ? 'Localização atualizada com sucesso!' : 'Localização cadastrada com sucesso!'
        ]);

        $this->fecharModal();
    }

    public function editar($id)
    {
        $localizacao = Localizacao::findOrFail($id);
        $this->localizacaoId = $id;
        $this->nome = $localizacao->nome;
        $this->descricao = $localizacao->descricao;
        $this->modalAberto = true;
    }

    public function confirmarDelecao($id)
    {
        $this->localizacaoParaDeletar = $id;
        $this->modalDelecaoAberto = true;
    }

    public function deletar()
    {
        if (!$this->localizacaoParaDeletar) {
            return;
        }

        $localizacao = Localizacao::withCount('animais')->find($this->localizacaoParaDeletar);

        if ($localizacao && $localizacao->animais_count > 0) {
            // ANTES: session()->flash('erro', 'Não é possível remover, pois existem animais nesta localização.');
            // DEPOIS:
            $this->dispatch('toast-notification', [
                'type' => 'error',
                'message' => 'Não é possível remover, pois existem animais nesta localização.'
            ]);
            $this->modalDelecaoAberto = false;
            return;
        }

        if ($localizacao) {
            $localizacao->delete();
            // ANTES: session()->flash('sucesso', 'Localização removida com sucesso!');
            // DEPOIS:
            $this->dispatch('toast-notification', ['message' => 'Localização removida com sucesso!']);
        }

        $this->modalDelecaoAberto = false;
    }

    public function render()
    {
        // 3. ATUALIZE: O método render com o filtro
        $localizacoes = Localizacao::withCount('animais')
            ->where(function ($query) {
                $query->where('nome', 'like', '%' . $this->search . '%')
                    ->orWhere('descricao', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nome')
            ->paginate(10);

        return view('livewire.gerenciar-localizacoes', [
            'localizacoes' => $localizacoes,
        ])->layout('layouts.app');
    }
}
