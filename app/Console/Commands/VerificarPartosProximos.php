<?php
// ARQUIVO: app/Console/Commands/VerificarPartosProximos.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Animal;
use App\Models\User;
use App\Notifications\PartoProximoNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class VerificarPartosProximos extends Command
{
    /**
     * A assinatura do comando, usada para chamá-lo via artisan.
     */
    protected $signature = 'app:verificar-partos-proximos';

    /**
     * A descrição do comando.
     */
    protected $description = 'Verifica fêmeas com parto previsto para os próximos dias e notifica os administradores.';

    /**
     * Lógica do comando.
     */
    public function handle()
    {
        $this->info('Iniciando a verificação de partos próximos...');

        // Encontra todos os animais que são fêmeas e estão com o status 'Gestante'
        $animaisGestantes = Animal::where('sexo', 'Fêmea')
                                  ->where('status_reprodutivo', 'Gestante')
                                  ->get();

        if ($animaisGestantes->isEmpty()) {
            $this->info('Nenhum animal gestante encontrado. Nenhuma notificação a ser enviada.');
            return;
        }

        // Encontra todos os utilizadores que devem ser notificados (ex: administradores)
        $admins = User::where('role', 'administrador')->get();

        if($admins->isEmpty()){
            $this->warn('Nenhum utilizador administrador encontrado para notificar.');
            return;
        }

        $notificacoesEnviadas = 0;

        foreach ($animaisGestantes as $animal) {
            // Usamos o nosso acessor para obter os dados do ciclo
            $ciclo = $animal->reproductive_cycle;

            if (isset($ciclo->dates['Previsão de Parto'])) {
                $dataParto = $ciclo->dates['Previsão de Parto'];
                $hoje = Carbon::today();

                // Alerta com 7 dias de antecedência ou menos
                $diasRestantes = $hoje->diffInDays($dataParto, false); // 'false' permite valores negativos

                if ($diasRestantes >= 0 && $diasRestantes <= 7) {

                    // **VERIFICAÇÃO ANTI-DUPLICADOS:**
                    // Verifica se já existe uma notificação para este animal e este parto
                    $notificacaoExistente = \DB::table('notifications')
                        ->where('notifiable_type', User::class)
                        ->whereJsonContains('data->animal_id', $animal->id)
                        ->whereJsonContains('data->mensagem', "parto da fêmea {$animal->identificacao} é em {$diasRestantes}")
                        ->exists();

                    if (!$notificacaoExistente) {
                        Notification::send($admins, new PartoProximoNotification($animal, $diasRestantes));
                        $this->line("Notificação enviada para o animal: {$animal->identificacao} (Faltam {$diasRestantes} dias)");
                        $notificacoesEnviadas++;
                    }
                }
            }
        }

        $this->info("Verificação concluída. {$notificacoesEnviadas} novas notificações foram enviadas.");
    }
}