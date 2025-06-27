<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Especie;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GerenciarEspecies extends Component
{
    use WithPagination;

    // Propriedades para o formulário
    public $especieId;
    public $nome;

    public $modalAberto = false;

    // Regras de validação
    protected function rules()
    {
        return [
            'nome' => [
                'required',
                'string',
                'min:3',
                // Garante que o nome seja único, ignorando o ID atual ao editar
                Rule::unique('especies')->ignore($this->especieId),
            ],
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

        Especie::updateOrCreate(['id' => $this->especieId], [
            'nome' => $this->nome,
        ]);

        session()->flash('sucesso', $this->especieId ? 'Espécie atualizada com sucesso!' : 'Espécie cadastrada com sucesso!');

        $this->fecharModal();
    }

    public function editar($id)
    {
        $especie = Especie::findOrFail($id);
        $this->especieId = $id;
        $this->nome = $especie->nome;

        $this->modalAberto = true;
    }

    public function deletar($id)
    {
        // Adicionar verificação se a espécie está em uso antes de deletar
        $especie = Especie::withCount('animais')->find($id);

        if ($especie->animais_count > 0) {
            session()->flash('erro', 'Não é possível remover a espécie, pois existem animais cadastrados nela.');
            return;
        }

        $especie->delete();
        session()->flash('sucesso', 'Espécie removida com sucesso!');
    }

    public function render()
    {
        $especies = Especie::orderBy('nome')->paginate(10);

        return view('livewire.gerenciar-especies', [
            'especies' => $especies,
        ])->layout('layouts.app'); // <-- ADICIONE ESTA PARTE TAMBÉM
    }
}
