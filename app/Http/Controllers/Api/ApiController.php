<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class ApiController extends Controller
{
    public function listarUsuarios()
    {
        return DB::table('usuario')->select('name', 'cpf', 'data_nasc', 'sexo', 'email', 'funcao', DB::raw('0 as checked'))->get();
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

    public function excluirUsuarios(Request $request)
    {
        $cpfs = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        foreach($cpfs as $cpf) {
            $res = DB::table('usuario')->where('cpf', $cpf)->delete();
        }

        return ["status" => ($res) ? 'ok' : 'erro'];        
    }

    public function editarUsuario($cpf, Request $request)
    {
        $usuario = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);
        unset($usuario['checked']);

        $res = DB::table('usuario')->where('cpf', $cpf)->update($usuario);

        if($usuario['funcao'] != "")
        {
            DB::table('usuario')->where('cpf', $cpf)->update(['ativo' => 1]);
        }
        else if($usuario['funcao'] == "")
        {
            DB::table('usuario')->where('cpf', $cpf)->update(['ativo' => 0]);
        }
 
        return $cpf;
    }
}
