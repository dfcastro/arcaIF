<?php
// ARQUIVO: app/Notifications/AgendaSanitariaVencendoNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\AgendaSanitaria;
use Carbon\Carbon; // Importamos o Carbon para usar o Carbon::today()

class AgendaSanitariaVencendoNotification extends Notification
{
    use Queueable;

    public $eventoAgenda;
    public $mensagem;
    public $icone;

    public function __construct(AgendaSanitaria $eventoAgenda)
    {
        $this->eventoAgenda = $eventoAgenda;
        $this->buildMessages();
    }

    private function buildMessages()
    {
        $animalIdentificacao = $this->eventoAgenda->animal->identificacao;
        $nomeEvento = $this->eventoAgenda->protocoloEvento->nome_evento;
        $dataAgendada = $this->eventoAgenda->data_agendada->startOfDay(); // Assegura que estamos a comparar o início do dia
        $hoje = Carbon::today(); // Usamos today() para ignorar a hora atual

        if ($dataAgendada->isPast()) {
            // Evento Atrasado
            // **CORREÇÃO APLICADA AQUI**
            $diasAtraso = $dataAgendada->diffInDays($hoje);
            
            if ($diasAtraso === 0) { // Se a data for hoje, mas o horário já passou
                 $this->mensagem = "Atenção: '{$nomeEvento}' para o animal {$animalIdentificacao} está agendado para hoje, mas pode estar atrasado.";
            } else {
                 $this->mensagem = "Atenção: '{$nomeEvento}' para o animal {$animalIdentificacao} está com {$diasAtraso} " . \Illuminate\Support\Str::plural('dia', $diasAtraso) . " de atraso.";
            }
           
            $this->icone = 'fa-solid fa-triangle-exclamation text-red-500';

        } else {
            // Evento Próximo
            // **CORREÇÃO APLICADA AQUI**
            $diasRestantes = $hoje->diffInDays($dataAgendada);
            
            if ($diasRestantes === 0) {
                $this->mensagem = "Lembrete: '{$nomeEvento}' para o animal {$animalIdentificacao} está agendado para hoje.";
            } else {
                $this->mensagem = "Lembrete: '{$nomeEvento}' para o animal {$animalIdentificacao} vence em {$diasRestantes} " . \Illuminate\Support\Str::plural('dia', $diasRestantes) . ".";
            }
            $this->icone = 'fa-solid fa-syringe text-blue-500';
        }
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'animal_id' => $this->eventoAgenda->animal->id,
            'mensagem' => $this->mensagem,
            'link' => route('animais.show', $this->eventoAgenda->animal->id),
            'icone' => $this->icone,
        ];
    }
}