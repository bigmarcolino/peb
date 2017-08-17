<?php

namespace App\Http\Controllers\AtendimentoApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Atendimento;
use App\Curva;
use App\DiagnosticoPrognostico;
use App\LocalEscoliose;
use App\Medidas;
use App\MobilidadeArticular;
use App\Paciente;
use App\PlanoFrontal;
use App\PlanoHorizontal;
use App\PlanoSagital;
use App\Vertebra;
use DB;
use StdClass;
use Illuminate\Support\Facades\Storage;

class AtendimentoApiController extends Controller
{
    public function addAtendimento($cpf, Request $request)
    { 
        $dados = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        if(isset($dados['atendimento']['menarca']))
            $dados['atendimento']['menarca'] = explode("T", $dados['atendimento']['menarca'])[0];

        if(isset($dados['atendimento']['data_raio_x']))
            $dados['atendimento']['data_raio_x'] = explode("T", $dados['atendimento']['data_raio_x'])[0];

        DB::transaction(function() use ($cpf, $request, $dados)
        {
            $paciente = Paciente::where('cpf', $cpf)->first();

            $atendimento = $dados["atendimento"];
            $curva = $dados["curva"];
            $diag_prog = $dados["diag_prog"];
            $local_escoliose = $dados["local_escoliose"];
            $medidas = $dados["medidas"];
            $mobilidade_articular = $dados["mobilidade_articular"];
            $plano_frontal = $dados["plano_frontal"];
            $plano_horizontal = $dados["plano_horizontal"];
            $plano_sagital = $dados["plano_sagital"];
            $vertebra = $dados["vertebra"];

            $novoAtendimento = new Atendimento($atendimento);
            $paciente->atendimento()->save($novoAtendimento);

            $qtd_atend = $paciente->atendimento()->count();
            $qtd_alg = strlen((string) $qtd_atend);

            if($qtd_alg == 1)
                $num_atend = "00" . $qtd_atend;
            else if($qtd_alg == 2)
                $num_atend = "0" . $qtd_atend;
            else if($qtd_alg == 3)
                $num_atend = $qtd_atend;

            Storage::makeDirectory("public/foto_atendimento/" . $paciente->nome . " - " . $paciente->cpf . "/" . $num_atend);            

            // Medidas

            $novoPlanoFrontal = null;
            $novoPlanoHorizontal = null;
            $novoPlanoSagital = null;
            $novoMobilidadeArticular = null;
            $salvarMedidas = false;

            if(!empty($plano_frontal)) {
                $novoPlanoFrontal = new PlanoFrontal($plano_frontal);
                $salvarMedidas = true;
            }

            if(!empty($plano_horizontal)) {
                $novoPlanoHorizontal = new PlanoHorizontal($plano_horizontal);
                $salvarMedidas = true;
            }

            if(!empty($plano_sagital)) {
                $novoPlanoSagital = new PlanoSagital($plano_sagital);
                $salvarMedidas = true;
            }

            if(!empty($mobilidade_articular)) {
                $novoMobilidadeArticular = new MobilidadeArticular($mobilidade_articular);
                $salvarMedidas = true;
            }

            if($salvarMedidas) {
                $novoMedidas = new Medidas($medidas);
                $novoAtendimento->medidas()->save($novoMedidas);

                if(!empty($plano_frontal))
                    $novoMedidas->plano_frontal()->save($novoPlanoFrontal);

                if(!empty($plano_horizontal))
                    $novoMedidas->plano_horizontal()->save($novoPlanoHorizontal);

                if(!empty($plano_sagital))
                    $novoMedidas->plano_sagital()->save($novoPlanoSagital);

                if(!empty($mobilidade_articular))
                    $novoMedidas->mobilidade_articular()->save($novoMobilidadeArticular);
            }
            else if (!empty($medidas)){
                $novoMedidas = new Medidas($medidas);
                $novoAtendimento->medidas()->save($novoMedidas);
            }

            // Diagnóstico Prognóstico

            $novoCurva = null;
            $novoLocalEscoliose = null;
            $novoVertebra = null;
            $salvarDiagProg = false;

            if(!empty($curva)) {
                $novoCurva = new Curva($curva);
                $salvarDiagProg = true;
            }

            if(!empty($local_escoliose)) {
                $novoLocalEscoliose = new LocalEscoliose($local_escoliose);
                $salvarDiagProg = true;
            }

            if(!empty($vertebra)) {
                $novoVertebra = new Vertebra($vertebra);
                $salvarDiagProg = true;
            }

            if($salvarDiagProg) {
                $novoDiagProg = new DiagnosticoPrognostico($diag_prog);
                $novoAtendimento->diag_prog()->save($novoDiagProg);

                if(!empty($curva))
                    $novoDiagProg->curva()->save($novoCurva);

                if(!empty($local_escoliose))
                    $novoDiagProg->local_escoliose()->save($novoLocalEscoliose);

                if(!empty($vertebra))
                    $novoDiagProg->vertebra()->save($novoVertebra);
            }
            else if (!empty($diag_prog)){
                $novoDiagProg = new DiagnosticoPrognostico($diag_prog);
                $novoAtendimento->diag_prog()->save($novoDiagProg);
            }
        });
    }

    public function getAtendimentos($cpf, $offset)
    {
        $paciente = Paciente::where('cpf', $cpf)->first();

        $todosAtendimentos = $paciente->atendimento();
        $atendCount = $todosAtendimentos->count();

        $limit = 5;
        $newOffset = 0;
        $atendimentos = null;

        $atendimentosResponse = array();
        $atendimentosNums = array();

        if($offset == -1) {
            if($atendCount > $limit) {
                $newOffset = $atendCount - $limit;

                for ($i = 1; $i <= $limit; $i++) {
                    array_push($atendimentosNums, $newOffset + $i);
                }
            }
            else {
                for ($i = 1; $i <= $atendCount; $i++) {
                    array_push($atendimentosNums, $atendCount);
                } 
            }

            $atendimentos = $todosAtendimentos->offset($newOffset)->limit($limit)->getResults();
        }
        else {
            $newOffset = $offset - 1;

            $atendimentos = $todosAtendimentos->offset($newOffset)->limit($limit)->getResults();

            if($atendCount - $newOffset > $limit)
                $num = $limit;
            else
                $num = $atendCount - $newOffset;
            
            for ($i = 1; $i <= $num; $i++) {
                array_push($atendimentosNums, $newOffset + $i);
            }
        }

        foreach ($atendimentos as $atendimento) {
            $var = new StdClass();

            $var->atendimento = $atendimento;

            if(isset($var->atendimento->menarca)) {
                $explode = explode("-", $var->atendimento->menarca);
                $var->atendimento->menarca = $explode[2] . "-" . $explode[1] . "-" . $explode[0];
            }

            if(isset($var->atendimento->data_raio_x)) {
                $explode = explode("-", $var->atendimento->data_raio_x);
                $var->atendimento->data_raio_x = $explode[2] . "-" . $explode[1] . "-" . $explode[0];
            }

            $explode = explode(" ", $var->atendimento->created_at);
            $data = explode("-", $explode[0]);
            $hora = $explode[1];
            $var->atendimento->data = $data[2] . "-" . $data[1] . "-" . $data[0];
            $var->atendimento->data_hora = $data[2] . "-" . $data[1] . "-" . $data[0] . " " . $hora;
            
            $medidas = $atendimento->medidas();
            $diag_prog = $atendimento->diag_prog();

            if($medidas->count() == 1) {
                $medidas = $medidas->getResults();
                $var->medidas = $medidas;

                $plano_frontal = $medidas->plano_frontal();
                if($plano_frontal->count() == 1)
                    $var->plano_frontal = $plano_frontal->getResults();

                $plano_horizontal = $medidas->plano_horizontal();
                if($plano_horizontal->count() == 1)
                    $var->plano_horizontal = $plano_horizontal->getResults();

                $plano_sagital = $medidas->plano_sagital();
                if($plano_sagital->count() == 1)
                    $var->plano_sagital = $plano_sagital->getResults();

                $mobilidade_articular = $medidas->mobilidade_articular();
                if($mobilidade_articular->count() == 1)
                    $var->mobilidade_articular = $mobilidade_articular->getResults();
            }

            if($diag_prog->count() == 1) {
                $diag_prog = $diag_prog->getResults();
                $var->diag_prog = $diag_prog;

                $curva = $diag_prog->curva();
                if($curva->count() == 1)
                    $var->curva = $curva->getResults();

                $local_escoliose = $diag_prog->local_escoliose();
                if($local_escoliose->count() == 1)
                    $var->local_escoliose = $local_escoliose->getResults();

                $vertebra = $diag_prog->vertebra();
                if($vertebra->count() == 1)
                    $var->vertebra = $vertebra->getResults();
            }

            array_push($atendimentosResponse, $var);
        }

        return ['quantidade' => $atendCount, 'atendimentos' => $atendimentosResponse, 'atendimentosNums' => $atendimentosNums];
    }

    public function uploadFotos($nome, $cpf, $num, Request $request)
    {
        $qtd_alg = strlen((string) $num);

        if($qtd_alg == 1)
            $num_atend = "00" . $num;
        else if($qtd_alg == 2)
            $num_atend = "0" . $num;
        else if($qtd_alg == 3)
            $num_atend = $num;

        $fotos = $request->allFiles();

        foreach($fotos as $foto) {
            $foto->store('public/foto_atendimento/' . $nome . " - " . $cpf . "/" . $num_atend);
        }
    }

    public function listarFotos($nome, $cpf, $num)
    {
        $qtd_alg = strlen((string) $num);

        if($qtd_alg == 1)
            $num_atend = "00" . $num;
        else if($qtd_alg == 2)
            $num_atend = "0" . $num;
        else if($qtd_alg == 3)
            $num_atend = $num;

        $pathPasta = 'public/foto_atendimento/' . $nome . " - " . $cpf . "/" . $num_atend;
        $pathFotos = Storage::allFiles($pathPasta);
        $fotos = array();
        
        foreach($pathFotos as $pathFoto) {
            array_push($fotos, "../storage/" . explode("/", $pathFoto, 2)[1]);
        }

        return $fotos;
    }

    public function getQtdFotosAtend($nome, $cpf, $num)
    {
        $qtd_alg = strlen((string) $num);

        if($qtd_alg == 1)
            $num_atend = "00" . $num;
        else if($qtd_alg == 2)
            $num_atend = "0" . $num;
        else if($qtd_alg == 3)
            $num_atend = $num;

        $pathPasta = 'public/foto_atendimento/' . $nome . " - " . $cpf . "/" . $num_atend;

        return count(Storage::allFiles($pathPasta));
    }
}