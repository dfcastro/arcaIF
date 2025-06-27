<?php
// ARQUIVO: app/Livewire/GerenciarAnimais.php
// INSTRUÇÃO: Substitua o conteúdo do seu arquivo por este código completo.

namespace App\Livewire;

use Livewire\Component;
use App\Models\Animal;
use App\Models\Especie;
use App\Models\Raca;
use Livewire\WithPagination;

class GerenciarAnimais extends Component
{
    use WithPagination;

    // Propriedades para o formulário
    public $animalId;
    public $especie_id;
    public $raca_id;
    public $identificacao;
    public $data_nascimento;
    public $sexo;
    public $observacoes;
    public $status = 'Ativo';

    public $modalAberto = false;
    public $termoBusca = '';

    public $racas = [];

    // Regras de validação
    protected function rules()
    {
        return [
            'especie_id' => 'required|exists:especies,id',
            'raca_id' => 'nullable|exists:racas,id',
            'identificacao' => 'required|string|min:3',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|in:Macho,Fêmea',
            'status' => 'required|in:Ativo,Vendido,Óbito',
            'observacoes' => 'nullable|string',
        ];
    }

    // Este método é chamado automaticamente quando $especie_id muda.
    public function updatedEspecieId($value)
    {
        $this->racas = Raca::where('especie_id', $value)->orderBy('nome')->get();
        $this->reset('raca_id');
    }

    // Limpa os campos do formulário
    private function resetInput()
    {
        $this->reset([
            'animalId',
            'especie_id',
            'raca_id',
            'identificacao',
            'data_nascimento',
            'sexo',
            'observacoes',
            'status'
        ]);
        $this->racas = []; // Limpa a lista de raças
        $this->status = 'Ativo';
    }

    // ===============================================
    // MÉTODOS QUE ESTAVAM FALTANDO
    // ===============================================
    public function abrirModal()
    {
        $this->resetInput();
        $this->modalAberto = true;
    }

    public function fecharModal()
    {
        $this->modalAberto = false;
    }
    // ===============================================

    public function salvar()
    {
        $this->validate();

        Animal::updateOrCreate(['id' => $this->animalId], [
            'especie_id' => $this->especie_id,
            'raca_id' => $this->raca_id,
            'identificacao' => $this->identificacao,
            'data_nascimento' => $this->data_nascimento,
            'sexo' => $this->sexo,
            'status' => $this->status,
            'observacoes' => $this->observacoes,
        ]);

        session()->flash('sucesso', $this->animalId ? 'Animal atualizado com sucesso!' : 'Animal cadastrado com sucesso!');
        $this->dispatch('animal-updated');
        $this->fecharModal();
    }

    public function editar($id)
    {
        $animal = Animal::findOrFail($id);
        $this->animalId = $id;
        $this->especie_id = $animal->especie_id;

        // Carrega as raças da espécie do animal que está sendo editado
        $this->racas = Raca::where('especie_id', $this->especie_id)->get();

        $this->raca_id = $animal->raca_id;
        $this->identificacao = $animal->identificacao;
        $this->data_nascimento = $animal->data_nascimento;
        $this->sexo = $animal->sexo;
        $this->status = $animal->status;
        $this->observacoes = $animal->observacoes;

        $this->modalAberto = true;
    }

    public function deletar($id)
    {
        Animal::find($id)->delete();
        session()->flash('sucesso', 'Animal removido com sucesso!');
        $this->dispatch('animal-updated'); 
    }

    public function render()
    {
        $especies = Especie::orderBy('nome')->get();

        $query = Animal::with(['especie', 'raca'])->latest();

        if ($this->termoBusca) {
            $query->where('identificacao', 'like', '%' . $this->termoBusca . '%');
        }

        $animais = $query->paginate(10);

        return view('livewire.gerenciar-animais', [
            'animais' => $animais,
            'especies' => $especies,
        ])->layout('layouts.app');
    }
}
