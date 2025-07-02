<?php
// ARQUIVO: app/Livewire/GerenciarAnimais.php

namespace App\Livewire;

use App\Models\Animal;
use App\Models\CategoriaAnimal;
use App\Models\Especie;
use App\Models\Localizacao;
use App\Models\Raca;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class GerenciarAnimais extends Component
{
    use WithPagination, AuthorizesRequests;

    // Propriedades do formulário
    public $animalId;
    public $especie_id, $raca_id, $localizacao_id, $categoria_animal_id;
    public $pai_id, $mae_id; // Propriedades para parentesco
    public $identificacao, $data_nascimento, $sexo, $observacoes, $status = 'Ativo';
    
    // Controlo de Modais
    public $modalAberto = false, $modalDelecaoAberto = false;
    public $animalParaDeletar;

    // Listas para dropdowns
    public $racas = [], $categoriasDisponiveis = [];
    public $paisDisponiveis = [], $maesDisponiveis = [];

    // Filtros e Ordenação
    public $termoBusca = '';
    public $filtroEspecie = '';
    public $filtroLocalizacao = '';
    public $filtroStatus = '';
    public $campoOrdenacao = 'created_at';
    public $direcaoOrdenacao = 'desc';

    public function rules()
    {
        return [
            'especie_id' => 'required|exists:especies,id',
            'raca_id' => 'nullable|exists:racas,id',
            'localizacao_id' => 'nullable|exists:localizacoes,id',
            'categoria_animal_id' => 'nullable|exists:categorias_animais,id',
            'pai_id' => 'nullable|exists:animais,id',
            'mae_id' => 'nullable|exists:animais,id',
            'identificacao' => 'required|string|min:3',
            'data_nascimento' => 'required|date',
            'sexo' => 'required|in:Macho,Fêmea',
            'status' => 'required|in:Ativo,Vendido,Óbito',
            'observacoes' => 'nullable|string',
        ];
    }

    public function ordenarPor($campo)
    {
        if ($this->campoOrdenacao === $campo) {
            $this->direcaoOrdenacao = $this->direcaoOrdenacao === 'asc' ? 'desc' : 'asc';
        } else {
            $this->direcaoOrdenacao = 'asc';
        }
        $this->campoOrdenacao = $campo;
        $this->resetPage();
    }

    public function limparFiltros()
    {
        $this->reset('termoBusca', 'filtroEspecie', 'filtroLocalizacao', 'filtroStatus');
        $this->resetPage();
    }

    public function updating($property)
    {
        if (in_array($property, ['termoBusca', 'filtroEspecie', 'filtroLocalizacao', 'filtroStatus'])) {
            $this->resetPage();
        }
    }
    
    public function updatedEspecieId($value)
    {
        if ($value) {
            $this->racas = Raca::where('especie_id', $value)->orderBy('nome')->get();
            $this->categoriasDisponiveis = CategoriaAnimal::where('especie_id', $value)->orderBy('nome')->get();
            $this->paisDisponiveis = Animal::where('especie_id', $value)->where('sexo', 'Macho')->orderBy('identificacao')->get();
            $this->maesDisponiveis = Animal::where('especie_id', $value)->where('sexo', 'Fêmea')->orderBy('identificacao')->get();
        } else {
            $this->racas = [];
            $this->categoriasDisponiveis = [];
            $this->paisDisponiveis = [];
            $this->maesDisponiveis = [];
        }

        $this->reset('raca_id', 'categoria_animal_id', 'pai_id', 'mae_id');
    }

    private function resetInput()
    {
        $this->resetExcept('termoBusca', 'filtroEspecie', 'filtroLocalizacao', 'filtroStatus', 'campoOrdenacao', 'direcaoOrdenacao');
        $this->racas = [];
        $this->categoriasDisponiveis = [];
        $this->paisDisponiveis = [];
        $this->maesDisponiveis = [];
        $this->status = 'Ativo';
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
        
        Animal::updateOrCreate(['id' => $this->animalId], [
            'especie_id' => $this->especie_id,
            'raca_id' => $this->raca_id,
            'localizacao_id' => $this->localizacao_id,
            'categoria_animal_id' => $this->categoria_animal_id,
            'pai_id' => $this->pai_id,
            'mae_id' => $this->mae_id,
            'identificacao' => $this->identificacao,
            'data_nascimento' => $this->data_nascimento,
            'sexo' => $this->sexo,
            'status' => $this->status,
            'observacoes' => $this->observacoes,
        ]);
        session()->flash('sucesso', $this->animalId ? 'Animal atualizado com sucesso!' : 'Animal cadastrado com sucesso!');
        $this->fecharModal();
    }

    public function editar($id)
    {
        $animal = Animal::findOrFail($id);
        $this->animalId = $id;
        $this->especie_id = $animal->especie_id;
        
        // Carrega todos os dropdowns necessários com base na espécie
        $this->updatedEspecieId($animal->especie_id);
        
        $this->raca_id = $animal->raca_id;
        $this->localizacao_id = $animal->localizacao_id;
        $this->categoria_animal_id = $animal->categoria_animal_id;
        $this->pai_id = $animal->pai_id;
        $this->mae_id = $animal->mae_id;
        $this->identificacao = $animal->identificacao;
        $this->data_nascimento = $animal->data_nascimento;
        $this->sexo = $animal->sexo;
        $this->status = $animal->status;
        $this->observacoes = $animal->observacoes;

        $this->modalAberto = true;
    }
    
    public function confirmarDelecao($id)
    {
        $this->animalParaDeletar = $id;
        $this->modalDelecaoAberto = true;
    }

    public function deletar()
    {
        $this->authorize('manage-system');
        if ($this->animalParaDeletar) {
            Animal::find($this->animalParaDeletar)->delete();
            session()->flash('sucesso', 'Animal removido com sucesso!');
            $this->dispatch('animal-updated');
        }
        $this->modalDelecaoAberto = false;
    }

    public function render()
    {
        $query = Animal::query()
            ->with(['especie', 'raca', 'localizacao', 'categoria'])
            ->when($this->termoBusca, fn($q) => $q->where('identificacao', 'like', '%' . $this->termoBusca . '%'))
            ->when($this->filtroEspecie, fn($q) => $q->where('especie_id', $this->filtroEspecie))
            ->when($this->filtroLocalizacao, fn($q) => $q->where('localizacao_id', $this->filtroLocalizacao))
            ->when($this->filtroStatus, fn($q) => $q->where('status', $this->filtroStatus));

        // Lógica de ordenação
        if ($this->campoOrdenacao === 'especie_id') {
            $query->join('especies', 'animais.especie_id', '=', 'especies.id')
                  ->orderBy('especies.nome', $this->direcaoOrdenacao)
                  ->select('animais.*'); // Evita conflito de colunas 'id'
        } else {
            $query->orderBy($this->campoOrdenacao, $this->direcaoOrdenacao);
        }

        return view('livewire.gerenciar-animais', [
            'animais' => $query->paginate(15),
            'especies' => Especie::orderBy('nome')->get(),
            'localizacoes' => Localizacao::orderBy('nome')->get(),
        ])->layout('layouts.app');
    }
}