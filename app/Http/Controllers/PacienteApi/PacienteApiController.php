<?php

namespace App\Http\Controllers\PacienteApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Paciente;
use DB;

class PacienteApiController extends Controller
{
    public function addPaciente(Request $request)
    { 
        $paciente = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);
        $paciente["data_nasc"] = explode("T", $paciente["data_nasc"])[0];
        Paciente::insert($paciente);
    }

    public function excluirPacientes(Request $request)
    {
        $cpfs = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        foreach($cpfs as $cpf) {
            $res = Paciente::where('cpf', $cpf)->delete();
        }

        return ["status" => ($res) ? 'ok' : 'erro'];        
    }

    public function getPacienteEdit($cpf)
    { 
        return Paciente::where('cpf', $cpf)->get();
    }

    public function editarPaciente(Request $request)
    {
        $paciente = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);
        $cpf = $paciente['cpf'];
        $paciente["data_nasc"] = explode("T", $paciente["data_nasc"])[0];
        Paciente::where('cpf', $cpf)->update($paciente);
    }
}