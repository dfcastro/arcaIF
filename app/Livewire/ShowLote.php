<?php
// ARQUIVO: app/Livewire/ShowLote.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lote;
use App\Models\Animal;
use Livewire\WithPagination;

class ShowLote extends Component
{
    use WithPagination;

    public Lote $lote;
    public $termoBusca = '';

    public function mount(Lote $lote)
    {
        $this->lote = $lote;
    }

    public function addAnimal($animalId)
    {
        // 'attach' adiciona o registro na tabela de ligação (animal_lote)
        $this->lote->animais()->attach($animalId);

        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Animal adicionado ao lote com sucesso!'
        ]);
    }

    public function removeAnimal($animalId)
    {
        // 'detach' remove o registro da tabela de ligação
        $this->lote->animais()->detach($animalId);

        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Animal removido do lote com sucesso!'
        ]);
    }

    public function render()
    {
        // Animais que já estão no lote
        $animaisNoLote = $this->lote->animais()->paginate(5, ['*'], 'animaisNoLotePage');

        // Animais que NÃO estão no lote, para a busca
        $animaisDisponiveis = Animal::where('status', 'Ativo')
            ->whereDoesntHave('lotes', function ($query) {
                $query->where('lote_id', $this->lote->id);
            })
            ->when($this->termoBusca, function ($query) {
                $query->where('identificacao', 'like', '%' . $this->termoBusca . '%');
            })
            ->paginate(5, ['*'], 'animaisDisponiveisPage');

        return view('livewire.show-lote', [
            'animaisNoLote' => $animaisNoLote,
            'animaisDisponiveis' => $animaisDisponiveis
        ])->layout('layouts.app');
    }
}
