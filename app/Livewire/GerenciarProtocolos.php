<?php
// ARQUIVO: app/Livewire/GerenciarProtocolos.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProtocoloSanitario;
use App\Models\Especie;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class GerenciarProtocolos extends Component
{
    use WithPagination;

    // Propriedades do formulário do Protocolo
    public $protocoloId;
    public $nome;
    public $especie_id;
    public $descricao;

    // Array para os eventos dinâmicos
    public $eventos = [];

    // Listas para dropdowns
    public $todasEspecies = [];
    public $tiposDeEvento = ['Vacina', 'Medicação', 'Vermifugo', 'Exame', 'Outro'];

    // Controlo dos modais
    public $modalAberto = false;
    public $modalDelecaoAberto = false;
    public $protocoloParaDeletar;

    protected function rules()
    {
        return [
            'nome' => 'required|string|min:3',
            'especie_id' => 'required|exists:especies,id',
            'descricao' => 'nullable|string',
            'eventos' => 'required|array|min:1',
            'eventos.*.nome_evento' => 'required|string|min:3',
            'eventos.*.tipo' => 'required|in:' . implode(',', $this->tiposDeEvento),
            'eventos.*.dias_apos_inicio' => 'required|integer|min:0',
            'eventos.*.instrucoes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'eventos.required' => 'É necessário adicionar pelo menos um evento ao protocolo.',
        'eventos.*.nome_evento.required' => 'O nome do evento é obrigatório.',
        'eventos.*.tipo.required' => 'O tipo de evento é obrigatório.',
        'eventos.*.dias_apos_inicio.required' => 'O dia de aplicação é obrigatório.',
    ];

    public function mount()
    {
        $this->todasEspecies = Especie::orderBy('nome')->get();
    }

    private function resetInput()
    {
        $this->resetExcept('todasEspecies', 'tiposDeEvento');
        $this->eventos = [];
        $this->resetErrorBag();
    }

    public function abrirModal()
    {
        $this->resetInput();
        $this->adicionarEvento(); // Começa com um evento em branco
        $this->modalAberto = true;
    }

    public function fecharModal()
    {
        $this->modalAberto = false;
    }

    // Funções para gerir a lista de eventos
    public function adicionarEvento()
    {
        $this->eventos[] = ['nome_evento' => '', 'tipo' => 'Vacina', 'dias_apos_inicio' => 0, 'instrucoes' => ''];
    }

    public function removerEvento($index)
    {
        unset($this->eventos[$index]);
        $this->eventos = array_values($this->eventos);
    }

    public function salvar()
    {
        $this->validate();

        DB::transaction(function () {
            $protocolo = ProtocoloSanitario::updateOrCreate(['id' => $this->protocoloId], [
                'nome' => $this->nome,
                'especie_id' => $this->especie_id,
                'descricao' => $this->descricao,
            ]);

            $protocolo->eventos()->delete(); // Apaga os eventos antigos para simplificar

            foreach ($this->eventos as $eventoData) {
                $protocolo->eventos()->create($eventoData);
            }
        });


        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => $this->protocoloId ? 'Protocolo atualizado com sucesso!' : 'Protocolo cadastrado com sucesso!'
        ]);
        $this->fecharModal();
    }

    public function editar($id)
    {
        $protocolo = ProtocoloSanitario::with('eventos')->findOrFail($id);
        $this->protocoloId = $id;
        $this->nome = $protocolo->nome;
        $this->especie_id = $protocolo->especie_id;
        $this->descricao = $protocolo->descricao;
        $this->eventos = $protocolo->eventos->toArray();
        $this->modalAberto = true;
    }

    public function confirmarDelecao($id)
    {
        $this->protocoloParaDeletar = $id;
        $this->modalDelecaoAberto = true;
    }

    public function deletar()
    {
        // Adicionar verificação se o protocolo está em uso na agenda
        ProtocoloSanitario::find($this->protocoloParaDeletar)->delete();
        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Protocolo removido com sucesso!'
        ]);
        $this->modalDelecaoAberto = false;
    }

    public function render()
    {
        return view('livewire.gerenciar-protocolos', [
            'protocolos' => ProtocoloSanitario::with('especie')->withCount('eventos')->paginate(10),
        ])->layout('layouts.app');
    }
}
