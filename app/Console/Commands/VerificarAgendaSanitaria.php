<?php
// ARQUIVO: app/Console/Commands/VerificarAgendaSanitaria.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AgendaSanitaria;
use App\Models\User;
use App\Notifications\AgendaSanitariaVencendoNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class VerificarAgendaSanitaria extends Command
{
    protected $signature = 'app:verificar-agenda-sanitaria';
    protected $description = 'Verifica eventos da agenda sanitária próximos ou vencidos e notifica os administradores.';

    public function handle()
    {
        $this->info('Iniciando a verificação da agenda sanitária...');

        // Encontra eventos agendados para os próximos 3 dias ou que já estão atrasados (até 30 dias de atraso)
        $eventos = AgendaSanitaria::where('status', 'Agendado')
            ->whereDate('data_agendada', '<=', Carbon::today()->addDays(3))
            ->whereDate('data_agendada', '>=', Carbon::today()->subDays(30)) // Para não notificar sobre eventos muito antigos
            ->with(['animal', 'protocoloEvento']) // Carrega as relações para otimizar
            ->get();

        if ($eventos->isEmpty()) {
            $this->info('Nenhum evento sanitário encontrado no período. Nenhuma notificação a ser enviada.');
            return;
        }

        $admins = User::where('role', 'administrador')->get();
        if ($admins->isEmpty()) {
            $this->warn('Nenhum utilizador administrador encontrado para notificar.');
            return;
        }

        $notificacoesEnviadas = 0;

        foreach ($eventos as $evento) {
            // Gera uma chave única para esta notificação para evitar duplicados
            // Ex: "agenda-15-2024-07-25" para o evento com ID 15 na data X
            $notificationKey = "agenda-" . $evento->id . "-" . $evento->data_agendada->format('Y-m-d');

            $notificacaoExistente = \DB::table('notifications')
                ->where('notifiable_type', User::class)
                ->whereJsonContains('data->mensagem', "para o animal {$evento->animal->identificacao}") // Filtro geral
                ->whereJsonContains('data->mensagem', "'{$evento->protocoloEvento->nome_evento}'") // Filtro específico
                ->exists();

            if (!$notificacaoExistente) {
                Notification::send($admins, new AgendaSanitariaVencendoNotification($evento));
                $this->line("Notificação enviada para o evento: '{$evento->protocoloEvento->nome_evento}' do animal {$evento->animal->identificacao}");
                $notificacoesEnviadas++;
            }
        }

        $this->info("Verificação concluída. {$notificacoesEnviadas} novas notificações foram enviadas.");
    }
}