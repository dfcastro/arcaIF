<?php
// ARQUIVO: app/Livewire/NotificationCenter.php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationCenter extends Component
{
    public $unreadCount;
    public $notifications;

    // Ouve um evento para atualizar as notificações quando outra parte do sistema precisar
    protected $listeners = ['notificationsUpdated' => 'mount'];

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->unreadCount = $user->unreadNotifications()->count();
            $this->notifications = $user->notifications()->latest()->take(10)->get();
        } else {
            $this->unreadCount = 0;
            $this->notifications = collect();
        }
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        if ($user) {
            $notification = $user->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                // Redireciona para o link da notificação
                return redirect($notification->data['link']);
            }
        }
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
            $this->mount(); // Recarrega os dados do componente
        }
    }

    public function render()
    {
        return view('livewire.notification-center');
    }
}