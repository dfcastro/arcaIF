<?php
// ARQUIVO: app/Models/ProtocoloSanitario.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProtocoloSanitario extends Model
{
    use HasFactory;

    protected $table = 'protocolos_sanitarios';
    protected $guarded = []; // Permite o preenchimento de todos os campos

    /**
     * Um protocolo pertence a uma espécie.
     */
    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }

    /**
     * Um protocolo tem muitos eventos/etapas.
     */
    public function eventos()
    {
        return $this->hasMany(ProtocoloEvento::class);
    }
}