<?php

namespace App\Http\Controllers\PacienteApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Paciente;
use App\Responsavel;
use DB;
use Illuminate\Support\Facades\Storage;

class PacienteApiController extends Controller
{
    public function addPaciente(Request $request)
    { 
        $dados = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        DB::transaction(function() use ($request, $dados)
        {
            $paciente = $dados["paciente"];
            $paciente["data_nasc"] = date('Y-m-d', strtotime($paciente["data_nasc"]));

            $novoPaciente = new Paciente($paciente);
            $novoPaciente->save();

            Storage::makeDirectory("public/foto_atendimento/" . $novoPaciente->nome . " - " . $novoPaciente->cpf);

            if(isset($dados["responsavel"])) {
                $responsavel = $dados["responsavel"];
                $novoResponsavel = new Responsavel($responsavel);
                $novoPaciente->responsavel()->save($novoResponsavel); 
            } 
        });
    }

    public function excluirPacientes(Request $request)
    {
        $cpfs = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        foreach($cpfs as $cpf) {
            $paciente = Paciente::where('cpf', $cpf)->first();
            $res = Paciente::where('cpf', $cpf)->delete();
            Storage::deleteDirectory("public/foto_atendimento/" . $paciente->nome . " - " . $paciente->cpf);
        }

        return ["status" => ($res) ? 'ok' : 'erro'];        
    }

    public function getPacienteEdit($cpf)
    { 
        $paciente = Paciente::where('cpf', $cpf)->first();
        $responsavel = $paciente->responsavel();

        if($responsavel->count() == 1)
            return ["paciente" => $paciente, "responsavel" => $responsavel->getResults()];
        else
            return ["paciente" => $paciente];
    }

    public function editarPaciente(Request $request)
    {
        $dados = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        DB::transaction(function() use ($request, $dados)
        {
            $paciente = $dados["paciente"];
            $cpf = $paciente['cpf'];
            $paciente["data_nasc"] = explode("T", $paciente["data_nasc"])[0];

            $pacienteAntigo = Paciente::where('cpf', $cpf)->first();
            $pasta_antiga = "public/foto_atendimento/" . $pacienteAntigo->nome . " - " . $pacienteAntigo->cpf;

            Paciente::where('cpf', $cpf)->update($paciente);

            Storage::move($pasta_antiga, "public/foto_atendimento/" . $paciente["nome"] . " - " . $paciente["cpf"]);

            if(isset($dados["responsavel"])) {
                $responsavel = $dados["responsavel"];
                Paciente::where('cpf', $cpf)->first()->responsavel()->update($responsavel);
            } 
        });
    }

    public function checkExistenciaCpfPaciente($cpf) {
        if (Paciente::where('cpf', $cpf)->exists()) {
           return 1;
        }
        else {
            return 0;
        }
    }

    public function checkExistenciaCpfResponsavel($cpf) {
        if (Responsavel::where('cpf', $cpf)->exists()) {
           return 1;
        }
        else {
            return 0;
        }
    }
}