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

class AtendimentoApiController extends Controller
{
    public function addAtendimento($cpf, Request $request)
    { 
        DB::transaction(function() use ($cpf, $request)
        {
            $dados = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);
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

    public function getAtendimentos($cpf)
    {
        $paciente = Paciente::where('cpf', $cpf)->first();

        $atendimentos = $paciente->atendimento()->getResults();
        $atendimentosResponse = array();

        foreach ($atendimentos as $atendimento) {
            $var = new StdClass();

            $var->atendimento = $atendimento;

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

        return $atendimentosResponse;
    } 
}