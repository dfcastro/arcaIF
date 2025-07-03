<?php
// ARQUIVO: app/Livewire/CalendarioSanitario.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AgendaSanitaria;
use App\Models\Animal;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalendarioSanitario extends Component
{
    public Carbon $dataAtual;
    public $modalAberto = false;
    public ?AgendaSanitaria $eventoSelecionado = null;

    // Propriedade para o teste de diagnóstico
    public $mensagemDeTeste = 'Nenhum botão clicado ainda.';

    public function mount()
    {
        $this->dataAtual = Carbon::today();
    }

    public function abrirModalEvento($eventoId)
    {
        $this->eventoSelecionado = AgendaSanitaria::with(['animal', 'protocoloEvento'])->find($eventoId);
        $this->modalAberto = true;
    }

    public function fecharModal()
    {
        $this->modalAberto = false;
        $this->eventoSelecionado = null;
    }

    public function irParaMesAnterior()
    {
        $this->dataAtual->subMonthNoOverflow();
        $this->mensagemDeTeste = 'Mês Anterior clicado em: ' . now()->toTimeString(); // Linha de teste
    }

    public function irParaMesSeguinte()
    {
        $this->dataAtual->addMonthNoOverflow();
        $this->mensagemDeTeste = 'Mês Seguinte clicado em: ' . now()->toTimeString(); // Linha de teste
    }
    
    public function irParaHoje()
    {
        $this->dataAtual = Carbon::today();
        $this->mensagemDeTeste = 'Hoje clicado em: ' . now()->toTimeString(); // Linha de teste
    }

    public function concluirEventoAgenda($agendaId)
    {
        $evento = AgendaSanitaria::find($agendaId);
        if ($evento) {
            $evento->update(['status' => 'Concluído', 'data_conclusao' => now()]);
            
            $animal = Animal::find($evento->animal_id);
            if ($animal) {
                $animal->movimentacoes()->create([
                    'data' => now(),
                    'tipo' => $evento->protocoloEvento->tipo,
                    'descricao' => 'Conclusão (agendada): ' . $evento->protocoloEvento->nome_evento,
                    'valor' => $evento->protocoloEvento->instrucoes,
                ]);
            }
            $this->dispatch('toast-notification', [
                'type' => 'sucess',
                'message' => 'Evento marcado como concluído!'
            ]);
                        $this->fecharModal();
        }
    }

    public function render()
    {
        // Lógica para os Cartões de Status
        $hoje = Carbon::today();
        $proximaSemana = Carbon::today()->addDays(7);

        $eventosAgendados = AgendaSanitaria::where('status', 'Agendado')
                                ->with(['animal', 'protocoloEvento'])
                                ->where('data_agendada', '<=', $proximaSemana)
                                ->orderBy('data_agendada', 'asc')
                                ->get();
        
        $eventosAtrasados = $eventosAgendados->filter(fn($e) => $e->data_agendada->isPast() && !$e->data_agendada->isToday());
        $eventosHoje = $eventosAgendados->filter(fn($e) => $e->data_agendada->isToday());
        $eventosProximos = $eventosAgendados->filter(fn($e) => $e->data_agendada->isFuture() && $e->data_agendada->lte($proximaSemana));
        
        // Lógica para o Calendário Mensal
        $primeiroDiaDoMes = $this->dataAtual->copy()->startOfMonth();
        $ultimoDiaDoMes = $this->dataAtual->copy()->endOfMonth();

        $eventosDoMes = AgendaSanitaria::whereBetween('data_agendada', [$primeiroDiaDoMes, $ultimoDiaDoMes])
            ->with(['animal.especie', 'protocoloEvento'])
            ->get()
            ->groupBy(fn($evento) => $evento->data_agendada->day);

        $diasDoMes = CarbonPeriod::create($primeiroDiaDoMes, $ultimoDiaDoMes)->toArray();


        // Passa todas as variáveis calculadas para a view
        return view('livewire.calendario-sanitario', [
            'eventosAtrasados' => $eventosAtrasados,
            'eventosHoje' => $eventosHoje,
            'eventosProximos' => $eventosProximos,
            'diasDoMes' => $diasDoMes,
            'eventosDoMes' => $eventosDoMes,
        ])->layout('layouts.app');
    }
}