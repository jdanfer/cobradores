<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cob_entrega extends Model
{
    use HasFactory;
    protected $table = 'cob_entregas';
    protected $fillable = [
        'fecha',
        'cobrador',
        'pesos',
        'hora',
        'obs',
        'arqueo',
        'usuario',
        'valida',
    ];
}
