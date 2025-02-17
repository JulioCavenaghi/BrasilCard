<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class DadosUsuarioService
{
    public function obterDadosUsuario($userId)
    {
        try {
            $dados = DB::table('users')
                ->join('contas_bancarias', 'users.id', '=', 'contas_bancarias.user_id')
                ->select('users.id', 'users.name', 'users.email', 'contas_bancarias.numero_conta', 'contas_bancarias.saldo')
                ->where('users.id', $userId)
                ->first();

            if (!$dados) {
                throw new Exception("Usuário ou conta bancária não encontrados.");
            }

            return [
                'success' => true,
                'dados' => (array) $dados
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}