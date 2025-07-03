<?php
// ARQUIVO: app/Livewire/GerenciarIngredientes.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ingrediente;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GerenciarIngredientes extends Component
{
    use WithPagination;

    // Propriedades do formulário
    public $ingredienteId;
    public $nome;
    public $preco_por_kg;
    public $proteina_bruta = 0;
    public $extrato_etereo = 0;
    public $fibra_bruta = 0;
    public $materia_mineral = 0;
    public $calcio = 0;
    public $fosforo = 0;

    // Controlo dos modais
    public $modalAberto = false;
    public $modalDelecaoAberto = false;
    public $ingredienteParaDeletar;

    protected function rules()
    {
        return [
            'nome' => ['required', 'string', 'min:3', Rule::unique('ingredientes')->ignore($this->ingredienteId)],
            'preco_por_kg' => 'required|numeric|min:0',
            'proteina_bruta' => 'required|numeric|min:0|max:100',
            'extrato_etereo' => 'required|numeric|min:0|max:100',
            'fibra_bruta' => 'required|numeric|min:0|max:100',
            'materia_mineral' => 'required|numeric|min:0|max:100',
            'calcio' => 'required|numeric|min:0|max:100',
            'fosforo' => 'required|numeric|min:0|max:100',
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
        $this->reset(); // Reseta todas as propriedades públicas para o seu estado inicial
        $this->resetErrorBag();
    }

    public function salvar()
    {
        $this->validate();

        Ingrediente::updateOrCreate(['id' => $this->ingredienteId], [
            'nome' => $this->nome,
            'preco_por_kg' => $this->preco_por_kg,
            'proteina_bruta' => $this->proteina_bruta,
            'extrato_etereo' => $this->extrato_etereo,
            'fibra_bruta' => $this->fibra_bruta,
            'materia_mineral' => $this->materia_mineral,
            'calcio' => $this->calcio,
            'fosforo' => $this->fosforo,
        ]);

        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' =>  $this->ingredienteId ? 'Ingrediente atualizado com sucesso!' : 'Ingrediente cadastrado com sucesso!'
        ]);
        $this->fecharModal();
    }

    public function editar($id)
    {
        $ingrediente = Ingrediente::findOrFail($id);
        $this->ingredienteId = $id;
        $this->nome = $ingrediente->nome;
        $this->preco_por_kg = $ingrediente->preco_por_kg;
        $this->proteina_bruta = $ingrediente->proteina_bruta;
        $this->extrato_etereo = $ingrediente->extrato_etereo;
        $this->fibra_bruta = $ingrediente->fibra_bruta;
        $this->materia_mineral = $ingrediente->materia_mineral;
        $this->calcio = $ingrediente->calcio;
        $this->fosforo = $ingrediente->fosforo;
        $this->modalAberto = true;
    }

    public function confirmarDelecao($id)
    {
        $this->ingredienteParaDeletar = $id;
        $this->modalDelecaoAberto = true;
    }

    public function deletar()
    {
        // Futuramente, verificar se o ingrediente está em uso em alguma fórmula
        Ingrediente::find($this->ingredienteParaDeletar)->delete();
        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Ingrediente removido com sucesso!'
        ]);
        $this->modalDelecaoAberto = false;
    }

    public function render()
    {
        return view('livewire.gerenciar-ingredientes', [
            'ingredientes' => Ingrediente::orderBy('nome')->paginate(10),
        ])->layout('layouts.app');
    }
}
