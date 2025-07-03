<?php
// ARQUIVO: app/Livewire/GerenciarUsuarios.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class GerenciarUsuarios extends Component
{
    use WithPagination;

    // Propriedades para o formulário de criação
    public $name, $email, $password, $password_confirmation;
    public $showCreateModal = false;

    public function changeRole(User $user, $newRole)
    {
        if (Gate::denies('access-admin-area')) {
            abort(403);
        }

        if (!in_array($newRole, ['administrador', 'operador'])) {
            return;
        }

        if ($user->id === auth()->id() && $newRole !== 'administrador') {
            $this->dispatch('toast-notification', [
                'type' => 'error',
                'message' => 'Não pode remover a sua própria função de administrador.'
            ]);
            return;
        }

        $user->role = $newRole;
        $user->save();


        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Função do utilizador ' . $user->name . ' atualizada para ' . $newRole . '.'
        ]);
    }

    // Abre o modal de criação
    public function openCreateModal()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation']);
        $this->showCreateModal = true;
    }

    // Fecha o modal de criação
    public function closeCreateModal()
    {
        $this->showCreateModal = false;
    }

    // Salva o novo utilizador
    public function storeUser()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'operador', // Define 'operador' como padrão
        ]);


        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Utilizador criado com sucesso!'
        ]);
        $this->closeCreateModal();
    }

    // NOVO MÉTODO PARA APAGAR UTILIZADOR
    public function deleteUser(User $user)
    {
        if (Gate::denies('access-admin-area')) {
            abort(403);
        }

        // Evita que o admin se apague a si mesmo
        if ($user->id === auth()->id()) {
            $this->dispatch('toast-notification', [
                'type' => 'error',
                'message' => 'Não pode apagar o seu próprio utilizador.'
            ]);
            return;
        }

        $user->delete();
        $this->dispatch('toast-notification', [
            'type' => 'sucess',
            'message' => 'Utilizador removido com sucesso!'
        ]);
    }


    public function render()
    {
        return view('livewire.gerenciar-usuarios', [
            'users' => User::orderBy('name')->paginate(10)
        ])->layout('layouts.app');
    }
}
