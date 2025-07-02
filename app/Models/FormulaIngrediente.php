<?php
// ARQUIVO: app/Models/FormulaIngrediente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulaIngrediente extends Model
{
    use HasFactory;
    public $table = 'formula_ingredientes';
    protected $guarded = [];
    public $timestamps = false;
}