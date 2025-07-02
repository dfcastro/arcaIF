<?php
// ARQUIVO: app/Livewire/PrevisaoConsumo.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CategoriaAnimal;
use App\Models\Ingrediente;
use App\Models\Especie;

class PrevisaoConsumo extends Component
{
    // Listas de dados para a view
    public $previsaoPorCategoria = [];
    public $necessidadeDeIngredientes = [];
    public $todasEspecies = [];

    // Propriedades para os cartões de resumo
    public $totalAnimais = 0;
    public $custoTotalAno = 0;

    // Propriedade para o filtro
    public $filtroEspecie = '';

    public function mount()
    {
        $this->todasEspecies = Especie::orderBy('nome')->get();
        // Opcional: define um filtro padrão ao carregar a página
        if ($this->todasEspecies->isNotEmpty()) {
            $this->filtroEspecie = $this->todasEspecies->first()->id;
        }
        $this->calcularPrevisao();
    }

    // É chamado sempre que uma propriedade pública (como o filtro) é alterada
    public function updatedFiltroEspecie()
    {
        $this->calcularPrevisao();
    }

    public function calcularPrevisao()
    {
        // Reseta os totais antes de recalcular
        $this->previsaoPorCategoria = [];
        $this->necessidadeDeIngredientes = [];
        $this->totalAnimais = 0;
        $this->custoTotalAno = 0;

        $query = CategoriaAnimal::query()
                    ->with(['animais', 'formulaRacao.ingredientes'])
                    // Aplica o filtro de espécie, se houver um selecionado
                    ->when($this->filtroEspecie, function($q) {
                        $q->where('especie_id', $this->filtroEspecie);
                    });

        $categorias = $query->get();
        
        $ingredientesAgregados = [];

        foreach ($categorias as $categoria) {
            $numeroAnimais = $categoria->animais->count();
            if ($numeroAnimais == 0) continue; // Pula categorias sem animais

            $this->totalAnimais += $numeroAnimais;

            $custoFormulaKg = 0;
            if ($categoria->formulaRacao) {
                foreach ($categoria->formulaRacao->ingredientes as $ingrediente) {
                    $percentual = $ingrediente->pivot->percentual_inclusao / 100;
                    $custoFormulaKg += $ingrediente->preco_por_kg * $percentual;

                    // Lógica para agregar o consumo de ingredientes
                    $consumoDiarioIngrediente = $categoria->consumo_diario_kg * $percentual;
                    $totalConsumoDiarioIngrediente = $consumoDiarioIngrediente * $numeroAnimais;
                    
                    if (!isset($ingredientesAgregados[$ingrediente->id])) {
                        $ingredientesAgregados[$ingrediente->id] = ['nome' => $ingrediente->nome, 'total_kg_dia' => 0, 'preco_kg' => $ingrediente->preco_por_kg];
                    }
                    $ingredientesAgregados[$ingrediente->id]['total_kg_dia'] += $totalConsumoDiarioIngrediente;
                }
            }
            
            $consumoTotalDia = $numeroAnimais * $categoria->consumo_diario_kg;
            $custoTotalDia = $consumoTotalDia * $custoFormulaKg;
            $custoAnualCategoria = $custoTotalDia * 365;
            $this->custoTotalAno += $custoAnualCategoria;

            $this->previsaoPorCategoria[] = [
                'nome' => $categoria->nome,
                'formula' => $categoria->formulaRacao->nome_formula ?? 'N/A',
                'numero_animais' => $numeroAnimais,
                'consumo_animal_dia' => $categoria->consumo_diario_kg,
                'custo_animal_dia' => $custoFormulaKg > 0 ? $categoria->consumo_diario_kg * $custoFormulaKg : 0,
                'consumo_total_dia' => $consumoTotalDia,
                'custo_total_mes' => $custoTotalDia * 30,
                'custo_total_ano' => $custoAnualCategoria,
            ];
        }
        
        foreach ($ingredientesAgregados as $id => $data) {
            $totalKgAno = $data['total_kg_dia'] * 365;
            $this->necessidadeDeIngredientes[] = [
                'nome' => $data['nome'],
                'total_kg_ano' => $totalKgAno,
                'sacos_50kg_ano' => $totalKgAno > 0 ? $totalKgAno / 50 : 0,
                'preco_kg' => $data['preco_kg'],
                'valor_total_ano' => $totalKgAno * $data['preco_kg'],
            ];
        }
    }

    public function render()
    {
        return view('livewire.previsao-consumo')->layout('layouts.app');
    }
}