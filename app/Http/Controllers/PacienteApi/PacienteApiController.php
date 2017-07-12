<?php

namespace App\Http\Controllers\PacienteApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class PacienteApiController extends Controller
{
    public function addPaciente(Request $request)
    { 
        $paciente = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);
        DB::table('paciente')->insert($paciente);
    }

    public function excluirPacientes(Request $request)
    {
        $cpfs = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        foreach($cpfs as $cpf) {
            $res = DB::table('paciente')->where('cpf', $cpf)->delete();
        }

        return ["status" => ($res) ? 'ok' : 'erro'];        
    }
}