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

        DB::transaction(function() use ($dados)
        {
            $paciente = $dados["paciente"];
            $paciente["data_nasc"] = date('Y-m-d', strtotime($paciente["data_nasc"]));

            $novoPaciente = new Paciente($paciente);
            $novoPaciente->save();

            if(isset($dados["responsavel"])) {
                $responsavel = $dados["responsavel"];

                if(Responsavel::where('cpf', $responsavel["cpf"])->exists())
                    Responsavel::where('cpf', $responsavel["cpf"])->first()->paciente()->save($novoPaciente);
                else {
                    $novoResponsavel = new Responsavel($responsavel);
                    $novoResponsavel->save();
                    $novoResponsavel->paciente()->save($novoPaciente); 
                }
            } 
        });

        $id = DB::table('paciente')->latest()->first()->id;
        return $id;
    }

    public function excluirPacientes(Request $request)
    {
        $ids = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        foreach($ids as $id) {
            $paciente = Paciente::where('id', $id)->first();
            $res = Paciente::where('id', $id)->delete();

            if($paciente != null && $paciente->cpf != null)
                Storage::deleteDirectory("public/foto_atendimento/" . $paciente->nome . " - " . $paciente->cpf);
        }

        return ["status" => ($res) ? 'ok' : 'erro'];        
    }

    public function getPacienteEdit($id)
    { 
        $paciente = Paciente::where('id', $id)->first();

        if($paciente == null)
            return ["paciente" => null];
        else if($paciente->responsavel_cpf != null) {
            $responsavel = Responsavel::where('cpf', $paciente->responsavel_cpf)->first();
            return ["paciente" => $paciente, "responsavel" => $responsavel];
        }
        else
            return ["paciente" => $paciente];
    }

    public function editarPaciente(Request $request)
    {
        $dados = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        if(Paciente::where('id', $dados["paciente"]["id"])->first() == null)
            return ["paciente" => null];

        DB::transaction(function() use ($dados)
        {
            $paciente = $dados["paciente"];
            $id = $paciente['id'];

            if(isset($paciente["data_nasc"]))
                $paciente["data_nasc"] = explode("T", $paciente["data_nasc"])[0];

            $pacienteAntigo = Paciente::where('id', $id)->first();

            if(isset($paciente["nome"]) && $pacienteAntigo->cpf != null) {
                $pasta_antiga = "public/foto_atendimento/" . $pacienteAntigo->nome . " - " . $pacienteAntigo->cpf;
                $pasta_nova = "public/foto_atendimento/" . $paciente["nome"] . " - " . $pacienteAntigo->cpf;
            }
            
            Paciente::where('id', $id)->update($paciente);

            if(isset($paciente["nome"]) && $pacienteAntigo->cpf != null && Storage::disk('public')->exists("foto_atendimento/" . $pacienteAntigo->nome . " - " . $pacienteAntigo->cpf))
                Storage::move($pasta_antiga, $pasta_nova);

            if(isset($dados["responsavel"]) && $pacienteAntigo->responsavel_cpf != null) {
                $responsavel = $dados["responsavel"];
                Responsavel::where('cpf', $pacienteAntigo->responsavel_cpf)->first()->update($responsavel);
            } 
        });
    }

    public function checkExistenciaCpfPaciente($cpf) {
        return Paciente::where('cpf', $cpf)->exists() ? 1 : 0;
    }

    public function checkExistenciaCpfResponsavel($cpf) {
        return Responsavel::where('cpf', $cpf)->exists() ? Responsavel::where('cpf', $cpf)->first() : 0;
    }
}