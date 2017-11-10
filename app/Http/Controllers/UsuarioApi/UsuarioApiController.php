<?php

namespace App\Http\Controllers\UsuarioApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Paciente;
use App\Usuario;
use DB;

class UsuarioApiController extends Controller
{
    public function listarUsuariosPacientes()
    {
        $usuarios = Usuario::select('name', 'cpf', 'data_nasc', 'sexo', 'email', 'funcao', DB::raw('0 as checked'))->get();
        $pacientes = Paciente::select('id', 'nome', 'cpf', 'data_nasc', 'email', DB::raw('0 as checked'))->get();
        return ['usuarios' => $usuarios, 'pacientes' => $pacientes];
    }

    public function qtdUsuariosInativos()
    {
    	$count = Usuario::where('ativo', 0)->count();
        return $count == 0 ? "" : $count;
    }

    public function excluirUsuarios(Request $request)
    {
        $cpfs = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        foreach($cpfs as $cpf)
            $res = Usuario::where('cpf', $cpf)->delete();     
    }

    public function editarUsuario(Request $request)
    {
        $usuario = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);
        $cpf = $usuario['cpf'];

        if(Usuario::where('cpf', $cpf)->first() == null)
            return ["usuario" => null];

        Usuario::where('cpf', $cpf)->update($usuario);

        if(isset($usuario['funcao'])) {
            if($usuario['funcao'] != "")
                Usuario::where('cpf', $cpf)->update(['ativo' => 1]);
            else if($usuario['funcao'] == "")
                Usuario::where('cpf', $cpf)->update(['ativo' => 0]);
        }
    }

    public function usuarioLogado($cpf)
    { 
        return ["nome" => Usuario::select('name')->where('cpf', $cpf)->first()];
    }
}