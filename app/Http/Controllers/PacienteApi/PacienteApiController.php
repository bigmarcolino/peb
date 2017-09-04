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

            if(isset($dados["responsavel"])) {
                $responsavel = $dados["responsavel"];

                if(Responsavel::where('cpf', $responsavel["cpf"])->exists()) {
                    Responsavel::where('cpf', $responsavel["cpf"])->first()->paciente()->save($novoPaciente);
                }
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

            if($paciente->cpf != null)
                Storage::deleteDirectory("public/foto_atendimento/" . $paciente->nome . " - " . $paciente->cpf);
        }

        return ["status" => ($res) ? 'ok' : 'erro'];        
    }

    public function getPacienteEdit($id)
    { 
        $paciente = Paciente::where('id', $id)->first();

        if($paciente->responsavel_cpf != null) {
            $responsavel = Responsavel::where('cpf', $paciente->responsavel_cpf)->first();
            return ["paciente" => $paciente, "responsavel" => $responsavel];
        }
        else
            return ["paciente" => $paciente];
    }

    public function editarPaciente(Request $request)
    {
        $dados = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        DB::transaction(function() use ($request, $dados)
        {
            $paciente = $dados["paciente"];
            $id = $paciente['id'];
            $paciente["data_nasc"] = explode("T", $paciente["data_nasc"])[0];

            $pacienteAntigo = Paciente::where('id', $id)->first();
            $pasta_antiga = "public/foto_atendimento/" . $pacienteAntigo->nome . " - " . $pacienteAntigo->cpf;
            $pasta_nova = "public/foto_atendimento/" . $paciente["nome"] . " - " . $paciente["cpf"];

            Paciente::where('id', $id)->update($paciente);

            if($pacienteAntigo->cpf == $paciente["cpf"] && $pacienteAntigo->nome != $paciente["nome"] && Storage::disk('public')->exists("foto_atendimento/" . $pacienteAntigo->nome . " - " . $pacienteAntigo->cpf)) {
                Storage::move($pasta_antiga, $pasta_nova);
            }

            if(isset($dados["responsavel"])) {
                $responsavel = $dados["responsavel"];
                Responsavel::where('cpf', $pacienteAntigo->responsavel_cpf)->first()->update($responsavel);
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
           return Responsavel::where('cpf', $cpf)->first();
        }
        else {
            return 0;
        }
    }
}