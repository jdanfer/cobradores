<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cob_mutuales extends Model
{
    use HasFactory;
    protected $table = 'cob_mutuales';
    protected $fillable = [
        'fecha',
        'cobrador',
        'pesos',
        'hora',
        'obs',
        'arqueo',
        'usuario',
        'vigilia',
    ];
}
