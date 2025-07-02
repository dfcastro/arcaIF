<?php
// ARQUIVO: app/Livewire/FormularRacao.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Especie;
use App\Models\Ingrediente;
use App\Models\FormulaRacao;
use Illuminate\Support\Facades\DB;

class FormularRacao extends Component
{
    // Propriedades da Fórmula
    public $nome_formula;
    public $especie_id;
    public $descricao;

    // Array principal que guarda os ingredientes da fórmula
    public $ingredientesDaFormula = [];

    // Propriedades para os totais calculados
    public $total_inclusao = 0;
    public $total_preco_kg = 0;
    public $total_proteina_bruta = 0;
    public $total_extrato_etereo = 0;
    public $total_fibra_bruta = 0;
    public $total_materia_mineral = 0;
    public $total_calcio = 0;
    public $total_fosforo = 0;

    // Listas para popular os selects
    public $todasEspecies = [];
    public $todosIngredientes = [];

    protected function rules()
    {
        return [
            'nome_formula' => 'required|string|min:5',
            'especie_id' => 'required|exists:especies,id',
            'ingredientesDaFormula' => 'array|min:1',
            'ingredientesDaFormula.*.ingrediente_id' => 'required',
            'ingredientesDaFormula.*.percentual_inclusao' => 'required|numeric|min:0.01|max:100',
        ];
    }
    
    protected $messages = [
        'nome_formula.required' => 'O nome da fórmula é obrigatório.',
        'especie_id.required' => 'É necessário selecionar uma espécie.',
        'ingredientesDaFormula.min' => 'A fórmula deve ter pelo menos um ingrediente.',
        'ingredientesDaFormula.*.ingrediente_id.required' => 'Selecione um ingrediente.',
        'ingredientesDaFormula.*.percentual_inclusao.required' => 'A % é obrigatória.',
    ];
    
    public function mount()
    {
        $this->todasEspecies = Especie::orderBy('nome')->get();
        $this->todosIngredientes = Ingrediente::orderBy('nome')->get();
        $this->adicionarIngrediente(); // Começa com um ingrediente na lista
    }

    public function adicionarIngrediente()
    {
        $this->ingredientesDaFormula[] = [
            'ingrediente_id' => '',
            'percentual_inclusao' => '',
        ];
        $this->calcularTotais();
    }

    public function removerIngrediente($index)
    {
        unset($this->ingredientesDaFormula[$index]);
        $this->ingredientesDaFormula = array_values($this->ingredientesDaFormula);
        $this->calcularTotais();
    }
    
    // Este é o método mágico que recalcula tudo sempre que algo muda
    public function updated($propertyName)
    {
        // Verifica se a mudança foi em algum campo dos ingredientes
        if (str_contains($propertyName, 'ingredientesDaFormula')) {
            $this->calcularTotais();
        }
    }

    public function calcularTotais()
    {
        // Reseta todos os totais
        $this->total_inclusao = 0;
        $this->total_preco_kg = 0;
        $this->total_proteina_bruta = 0;
        $this->total_extrato_etereo = 0;
        $this->total_fibra_bruta = 0;
        $this->total_materia_mineral = 0;
        $this->total_calcio = 0;
        $this->total_fosforo = 0;

        foreach ($this->ingredientesDaFormula as $item) {
            $percentual = floatval($item['percentual_inclusao'] ?? 0) / 100;
            if ($percentual > 0 && !empty($item['ingrediente_id'])) {
                $ingrediente = $this->todosIngredientes->find($item['ingrediente_id']);
                
                if($ingrediente) {
                    $this->total_inclusao += $percentual * 100;
                    $this->total_preco_kg += $ingrediente->preco_por_kg * $percentual;
                    $this->total_proteina_bruta += $ingrediente->proteina_bruta * $percentual;
                    $this->total_extrato_etereo += $ingrediente->extrato_etereo * $percentual;
                    $this->total_fibra_bruta += $ingrediente->fibra_bruta * $percentual;
                    $this->total_materia_mineral += $ingrediente->materia_mineral * $percentual;
                    $this->total_calcio += $ingrediente->calcio * $percentual;
                    $this->total_fosforo += $ingrediente->fosforo * $percentual;
                }
            }
        }
    }

    public function salvarFormula()
    {
        $this->validate();

        if (round($this->total_inclusao) != 100) {
            $this->addError('total_inclusao', 'A soma dos percentuais de inclusão deve ser exatamente 100%.');
            return;
        }

        DB::transaction(function () {
            $formula = FormulaRacao::create([
                'nome_formula' => $this->nome_formula,
                'especie_id' => $this->especie_id,
                'descricao' => $this->descricao,
            ]);

            foreach ($this->ingredientesDaFormula as $item) {
                $formula->ingredientes()->attach($item['ingrediente_id'], [
                    'percentual_inclusao' => $item['percentual_inclusao']
                ]);
            }
        });

        session()->flash('sucesso', 'Fórmula de ração salva com sucesso!');
        $this->redirect('/ingredientes'); // Ou para uma futura lista de fórmulas
    }

    public function render()
    {
        return view('livewire.formular-racao')->layout('layouts.app');
    }
}