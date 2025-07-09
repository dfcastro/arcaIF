<?php
// ARQUIVO: app/Notifications/PartoProximoNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Animal; // Importamos o modelo Animal

class PartoProximoNotification extends Notification
{
    use Queueable;

    // Propriedade para guardar o animal relacionado à notificação
    public $animal;
    public $dias_restantes;

    /**
     * Cria uma nova instância da notificação.
     *
     * @param Animal $animal O animal que está com parto próximo
     * @param int $dias_restantes Quantos dias faltam para o parto
     */
    public function __construct(Animal $animal, int $dias_restantes)
    {
        $this->animal = $animal;
        $this->dias_restantes = $dias_restantes;
    }

    /**
     * Define os canais de entrega da notificação (ex: database, mail, etc.).
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Queremos que esta notificação seja guardada na base de dados.
        return ['database'];
    }

    /**
     * Converte a notificação para um array para ser guardado na base de dados.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // Esta é a estrutura de dados que será guardada na coluna 'data' da tabela 'notifications'
        $mensagem = "A previsão de parto da fêmea {$this->animal->identificacao} é em {$this->dias_restantes} " . \Illuminate\Support\Str::plural('dia', $this->dias_restantes) . ".";

        if ($this->dias_restantes === 0) {
            $mensagem = "A previsão de parto da fêmea {$this->animal->identificacao} é hoje!";
        }

        return [
            'animal_id' => $this->animal->id,
            'mensagem' => $mensagem,
            'link' => route('animais.show', $this->animal->id), // Link para a ficha do animal
            'icone' => 'fa-solid fa-baby-carriage', // Um ícone do Font Awesome para a UI
        ];
    }
}