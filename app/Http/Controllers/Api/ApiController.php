<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DB;

class ApiController extends Controller
{
    public function listarUsuarios()
    {
        return DB::table('usuario')->select('name','cpf', 'data_nasc', 'sexo', 'email', 'funcao', DB::raw('0 as checked'))->get();
    }

    public function qtdUsuariosInativos()
    {
    	$count = DB::table('usuario')->where('ativo', 0)->count();

    	if ($count == 0) {
    		return "";
    	}
        else {
        	return $count;
        }
    }

    public function excluirUsuario($cpf)
    {
        $res = DB::table('usuario')->where('cpf', $cpf)->delete();
        return ["status" => ($res) ? 'ok' : 'erro'];
    }
}
