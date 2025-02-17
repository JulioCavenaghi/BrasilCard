<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;

    protected $table = 'transacoes';

    protected $fillable = [
        'numero_conta',
        'descricao',
        'valor',
        'tipo',
        'transacao_origem',
        'transacao_destino'
    ];

    public function contaBancaria() {
        return $this->belongsTo(ContaBancaria::class, 'numero_conta', 'numero_conta');
    }
}
