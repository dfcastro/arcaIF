<?php
// ARQUIVO: app/Livewire/ShowAnimal.php

namespace App\Livewire;

use App\Models\Animal;
use App\Models\EventoReprodutivo;
use App\Models\Movimentacao;
use App\Models\ProtocoloSanitario;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\AgendaSanitaria;

class ShowAnimal extends Component
{
    public Animal $animal;

    // --- Propriedades para Formulários e Modais ---
    // Evento Reprodutivo
    public $tipo_evento_reprodutivo = 'Cobrição';
    public $data_evento_reprodutivo;
    public $status_reprodutivo = 'Realizado';
    public $macho_relacionado_id;
    public $observacoes_reprodutivas;
    public $machosDisponiveis = [];

    // Evento Manual (Histórico)
    public $data;
    public $tipo = 'Observação';
    public $descricao;
    public $valor;
    public $unidade = 'Kg';

    // Modal de Edição de Movimentação
    public ?Movimentacao $movimentacaoEmEdicao = null;
    public $showEditModal = false;
    public $dataEdicao, $tipoEdicao, $descricaoEdicao, $valorEdicao, $unidadeEdicao;

    // Módulo de Sanidade
    public $protocolosDisponiveis = [];
    public $protocoloSelecionado;
    public $showAplicarProtocoloModal = false;
    public $showEventoReprodutivoModal = false;


    // --- Propriedades para Estado da Página ---
    public $activeTab = 'Reprodutivo';

    public function mount(Animal $animal)
    {
        // Carrega todas as relações necessárias de uma só vez para otimizar as consultas
        $this->animal = $animal->load('especie', 'raca', 'localizacao', 'categoria', 'pai', 'mae', 'movimentacoes', 'agendaSanitaria.protocoloEvento', 'eventosReprodutivos.animalRelacionado');

        // Inicializa as datas dos formulários
        $this->data_evento_reprodutivo = now()->format('Y-m-d');
        $this->data = now()->format('Y-m-d');

        // Carrega dados para os dropdowns
        $this->machosDisponiveis = Animal::where('especie_id', $this->animal->especie_id)->where('sexo', 'Macho')->orderBy('identificacao')->get();
        $this->protocolosDisponiveis = ProtocoloSanitario::where('especie_id', $this->animal->especie_id)->orderBy('nome')->get();
    }

    /**
     * NOVO MÉTODO PARA OBTER O ÍCONE DA ESPÉCIE
     * Este método é público para que a view possa chamá-lo.
     */
    public function getIconeEspecie()
    {
        switch (strtolower($this->animal->especie->nome)) {
            case 'bovino':
            case 'bovinos':
                return 'fa-cow';
            case 'ovino':
            case 'ovinos':
                return 'fa-sheep';
            case 'suíno':
            case 'suínos':
                return 'fa-piggy-bank';
            case 'ave':
            case 'aves':
                return 'fa-dove';
            case 'equino':
            case 'equinos':
                return 'fa-horse';
            default:
                return 'fa-paw';
        }
    }



    // --- MÉTODOS DE AÇÃO (SALVAR, DELETAR, ETC) ---

    public function salvarEventoReprodutivo()
    {
        $validated = $this->validate([
            'tipo_evento_reprodutivo' => 'required|in:Cobrição,Inseminação,Diagnóstico de Gestação,Previsão de Parto,Parto,Aborto',
            'data_evento_reprodutivo' => 'required|date',
            'status_reprodutivo' => 'required|in:Agendado,Realizado,Falhou',
            'macho_relacionado_id' => 'nullable|exists:animais,id',
            'observacoes_reprodutivas' => 'nullable|string',
        ]);

        // CORREÇÃO: Mapeia os dados validados para os nomes corretos das colunas da tabela
        $this->animal->eventosReprodutivos()->create([
            'tipo' => $validated['tipo_evento_reprodutivo'],
            'data' => $validated['data_evento_reprodutivo'],
            'status' => $validated['status_reprodutivo'],
            'animal_relacionado_id' => $validated['macho_relacionado_id'],
            'observacoes' => $validated['observacoes_reprodutivas'],
        ]);


        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Evento reprodutivo registado!'
        ]);

        $this->showEventoReprodutivoModal = false;
        $this->animal->refresh(); // Recarrega os dados do animal para a view
    }


    public function deleteEventoReprodutivo($id)
    {
        EventoReprodutivo::find($id)->delete();

        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Evento reprodutivo removido!'
        ]);
        $this->animal->refresh();
    }

    public function aplicarProtocolo()
    {
        $this->validate(['protocoloSelecionado' => 'required']);
        if (in_array($this->protocoloSelecionado, $this->getProtocolosAplicadosIds())) {

            $this->dispatch('toast-notification', [
                'type' => 'error',
                'message' => 'Este protocolo já foi aplicado a este animal.'
            ]);
            return;
        }
        $protocolo = ProtocoloSanitario::with('eventos')->find($this->protocoloSelecionado);
        if (!$protocolo) {
            return;
        }

        foreach ($protocolo->eventos as $evento) {
            $dataAgendada = Carbon::parse($this->animal->data_nascimento)->addDays($evento->dias_apos_inicio);
            $this->animal->agendaSanitaria()->create(['protocolo_evento_id' => $evento->id, 'data_agendada' => $dataAgendada, 'status' => 'Agendado']);
        }

        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Protocolo "' . $protocolo->nome . '" aplicado com sucesso!'
        ]);

        $this->showAplicarProtocoloModal = false;
        $this->animal->refresh();
    }

    public function concluirEventoAgenda($id)
    {
        $evento = AgendaSanitaria::find($id);
        if ($evento) {
            $evento->update(['status' => 'Concluído', 'data_conclusao' => now()]);
            $this->animal->movimentacoes()->create([
                'data' => now(),
                'tipo' => $evento->protocoloEvento->tipo,
                'descricao' => 'Conclusão (agendada): ' . $evento->protocoloEvento->nome_evento,
                'valor' => $evento->protocoloEvento->instrucoes,
            ]);

            $this->dispatch('toast-notification', [
                'type' => 'sucess',
                'message' => 'Evento sanitário concluído!'
            ]);
            $this->animal->refresh();
        }
    }

    public function salvarMovimentacao()
    {
        $regras = ['data' => 'required|date', 'tipo' => 'required|string', 'descricao' => 'required|string|min:3'];
        if ($this->tipo === 'Pesagem') {
            $regras['valor'] = 'required|numeric';
            $regras['unidade'] = 'required|in:Kg,@';
        } else {
            $regras['valor'] = 'nullable|string|max:255';
        }
        $dadosValidados = $this->validate($regras);
        if ($this->tipo === 'Pesagem') $dadosValidados['valor'] = $this->valor . ' ' . $this->unidade;

        $this->animal->movimentacoes()->create($dadosValidados);
        if (in_array($this->tipo, ['Venda', 'Óbito'])) {
            $this->animal->status = $this->tipo === 'Venda' ? 'Vendido' : 'Óbito';
            $this->animal->save();
        }

        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Evento registado com sucesso!'
        ]);
        $this->resetAddForm();
        $this->animal->refresh();
    }

    public function startEditing(Movimentacao $movimentacao)
    {
        $this->movimentacaoEmEdicao = $movimentacao;
        $this->dataEdicao = Carbon::parse($movimentacao->data)->format('Y-m-d');
        $this->tipoEdicao = $movimentacao->tipo;
        $this->descricaoEdicao = $movimentacao->descricao;
        if ($this->tipoEdicao === 'Pesagem') {
            $partes = explode(' ', $movimentacao->valor);
            $this->valorEdicao = floatval($partes[0]) ?? null;
            $this->unidadeEdicao = $partes[1] ?? 'Kg';
        } else {
            $this->valorEdicao = $movimentacao->valor;
        }
        $this->showEditModal = true;
    }
    public function updateMovimentacao()
    {
        // ... (código existente)
        $this->showEditModal = false;
        $this->animal->refresh();
    }
    public function deleteMovimentacao($movimentacaoId)
    {
        Movimentacao::find($movimentacaoId)->delete();

        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Evento removido!'
        ]);
        $this->animal->refresh();
    }

    // --- MÉTODOS PARA ABRIR/FECHAR MODAIS ---
    public function abrirModalProtocolo()
    {
        $this->showAplicarProtocoloModal = true;
    }
    public function abrirModalReprodutivo()
    {
        $this->showEventoReprodutivoModal = true;
    }

    // --- MÉTODOS AUXILIARES ---
    private function resetAddForm()
    {
        $this->reset(['tipo', 'descricao', 'valor', 'unidade']);
        $this->data = now()->format('Y-m-d');
        $this->unidade = 'Kg';
        $this->resetErrorBag();
    }

    private function prepararDadosGrafico()
    {
        $pesagens = $this->animal->movimentacoes->where('tipo', 'Pesagem')->sortBy('data');
        $labels = $pesagens->map(fn($item) => Carbon::parse($item->data)->format('d/m/Y'))->values();
        $data = $pesagens->map(function ($item) {
            $partes = explode(' ', $item->valor);
            $valorNumerico = floatval($partes[0] ?? 0);
            $unidade = $partes[1] ?? 'Kg';
            return (strtoupper($unidade) === '@') ? $valorNumerico * 15 : $valorNumerico;
        })->values();

        return ['labels' => $labels, 'data' => $data];
    }

    public function getProtocolosAplicadosIds()
    {
        return $this->animal->agendaSanitaria()
            ->with('protocoloEvento.protocoloSanitario')
            ->get()->pluck('protocoloEvento.protocoloSanitario.id')->unique()->toArray();
    }

    public function getIconeEventoReprodutivo($tipo)
    {
        // >> CORREÇÃO: Usando mb_strtolower para lidar com caracteres especiais <<
        switch (mb_strtolower($tipo, 'UTF-8')) {
            case 'cobrição':
            case 'inseminação':
                return 'fa-heart';
            case 'diagnóstico de gestação':
                return 'fa-search-plus'; // Ícone melhorado
            case 'previsão de parto':
                return 'fa-baby-carriage';
            case 'parto':
                return 'fa-birthday-cake';
            case 'aborto':
                return 'fa-times-circle';
            default:
                return 'fa-venus-mars';
        }
    }
    // --- RENDER ---
    public function render()
    {
        // Prepara os dados do gráfico a cada renderização
        $chartData = $this->prepararDadosGrafico();
        // Envia o evento para o frontend com os novos dados
        $this->dispatch('update-pesagem-chart', data: $chartData);

        // Prepara os outros dados para a view
        $movimentacoesFiltradas = $this->activeTab == 'Todos'
            ? $this->animal->movimentacoes
            : $this->animal->movimentacoes->where('tipo', $this->activeTab);

        $protocolosAplicados = $this->getProtocolosAplicadosIds();

        return view('livewire.show-animal', [
            'movimentacoesFiltradas' => $movimentacoesFiltradas,
            'protocolosAplicadosIds' => $protocolosAplicados,
            'pesagemChartData' => $chartData, // Passa os dados para a inicialização do gráfico
        ])->layout('layouts.app');
    }
}
