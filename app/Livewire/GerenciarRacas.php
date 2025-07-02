<?php
// ARQUIVO: app/Livewire/GerenciarRacas.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Raca;
use App\Models\Especie;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GerenciarRacas extends Component
{
    use WithPagination;

    // Propriedades do formulário
    public $racaId;
    public $nome;
    public $especie_id;
    
    public $modalAberto = false;

    // >> INÍCIO DAS NOVAS PROPRIEDADES PARA O MODAL DE EXCLUSÃO
    public $modalDelecaoAberto = false;
    public $racaParaDeletar;
    // << FIM DAS NOVAS PROPRIEDADES

    // ... (O seu método rules() e messages continuam aqui) ...
    protected function rules()
    {
        return [
            // O nome da raça deve ser único para aquela espécie específica.
            'nome' => [
                'required',
                'string',
                'min:3',
                Rule::unique('racas')->where(function ($query) {
                    return $query->where('especie_id', $this->especie_id)
                                 ->where('id', '!=', $this->racaId);
                }),
            ],
            'especie_id' => 'required|exists:especies,id',
        ];
    }

    protected $messages = [
        'nome.required' => 'O nome da raça é obrigatório.',
        'nome.unique' => 'Este nome de raça já existe para a espécie selecionada.',
        'especie_id.required' => 'É obrigatório selecionar a espécie.',
    ];


    // ... (Os seus métodos abrirModal, fecharModal, resetInput, salvar e editar continuam aqui) ...
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
        $this->reset(['racaId', 'nome', 'especie_id']);
        $this->resetErrorBag();
    }

    public function salvar()
    {
        $this->validate();

        Raca::updateOrCreate(['id' => $this->racaId], [
            'nome' => $this->nome,
            'especie_id' => $this->especie_id,
        ]);

        session()->flash('sucesso', $this->racaId ? 'Raça atualizada com sucesso!' : 'Raça cadastrada com sucesso!');

        $this->fecharModal();
    }

    public function editar($id)
    {
        $raca = Raca::findOrFail($id);
        $this->racaId = $id;
        $this->nome = $raca->nome;
        $this->especie_id = $raca->especie_id;

        $this->modalAberto = true;
    }


    // >> INÍCIO DOS NOVOS MÉTODOS PARA O MODAL DE EXCLUSÃO

    /**
     * Abre o modal de confirmação de exclusão.
     */
    public function confirmarDelecao($racaId)
    {
        $this->racaParaDeletar = $racaId;
        $this->modalDelecaoAberto = true;
    }

    /**
     * Deleta a raça após a confirmação.
     */
    public function deletar()
    {
        if (!$this->racaParaDeletar) {
            return;
        }

        $raca = Raca::withCount('animais')->find($this->racaParaDeletar);

        if ($raca && $raca->animais_count > 0) {
            session()->flash('erro', 'Não é possível remover esta raça, pois existem animais cadastrados nela.');
            $this->modalDelecaoAberto = false; // Fecha o modal
            return;
        }

        if ($raca) {
            $raca->delete();
            session()->flash('sucesso', 'Raça removida com sucesso!');
        }

        $this->modalDelecaoAberto = false; // Fecha o modal
    }
    // << FIM DOS NOVOS MÉTODOS

    public function render()
    {
        return view('livewire.gerenciar-racas', [
            'racas' => Raca::with('especie')->orderBy('nome')->paginate(10),
            'especies' => Especie::orderBy('nome')->get(),
        ])->layout('layouts.app');
    }
}