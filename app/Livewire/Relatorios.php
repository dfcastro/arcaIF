<?php
// ARQUIVO: app/Livewire/Relatorios.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lote;
use App\Models\Animal;

class Relatorios extends Component
{
    // Propriedades para os filtros
    public $tipoRelatorio = '';
    public $loteId;

    // Propriedade para guardar os dados do relatório
    public $dadosRelatorio = [];
    public $loteSelecionado;

    /**
     * Gera o relatório com base nos filtros selecionados.
     */
    public function gerarRelatorio()
    {
        $this->validate([
            'tipoRelatorio' => 'required',
            'loteId' => 'required_if:tipoRelatorio,animais_por_lote',
        ]);
        
        $this->dadosRelatorio = []; // Limpa dados anteriores

        if ($this->tipoRelatorio === 'animais_por_lote') {
            $this->loteSelecionado = Lote::find($this->loteId);
            $this->dadosRelatorio = Animal::whereHas('lotes', function($query) {
                $query->where('lote_id', $this->loteId);
            })->with('especie', 'raca')->get();
        }

        // Futuramente, outros relatórios podem ser adicionados aqui
        // if ($this->tipoRelatorio === 'outro_tipo') { ... }
    }

    public function render()
    {
        return view('livewire.relatorios', [
            'lotes' => Lote::orderBy('nome')->get()
        ])->layout('layouts.app');
    }
}
