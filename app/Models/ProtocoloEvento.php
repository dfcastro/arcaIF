<?php
// ARQUIVO: app/Models/ProtocoloEvento.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProtocoloEvento extends Model
{
    use HasFactory;

    protected $table = 'protocolo_eventos';
    protected $guarded = [];

    /**
     * Um evento pertence a um protocolo sanitário.
     */
    public function protocoloSanitario()
    {
        return $this->belongsTo(ProtocoloSanitario::class);
    }
}