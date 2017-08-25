<?php

namespace App\Http\Controllers\AtendimentoApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Atendimento;
use App\Curva;
use App\DiagnosticoPrognostico;
use App\Medidas;
use App\MobilidadeArticular;
use App\Paciente;
use App\PlanoFrontal;
use App\PlanoHorizontalMilimetros;
use App\PlanoHorizontalGraus;
use App\PlanoSagital;
use App\VertebraApice;
use App\VertebraLimite;
use DB;
use StdClass;
use Illuminate\Support\Facades\Storage;
use DateTime;

class AtendimentoApiController extends Controller
{
    public function addAtendimento($id, Request $request)
    { 
        $dados = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        if(isset($dados['atendimento']['menarca']))
            $dados['atendimento']['menarca'] = explode("T", $dados['atendimento']['menarca'])[0];

        if(isset($dados['atendimento']['data_raio_x']))
            $dados['atendimento']['data_raio_x'] = explode("T", $dados['atendimento']['data_raio_x'])[0];

        DB::transaction(function() use ($id, $request, $dados)
        {
            $paciente = Paciente::where('id', $id)->first();

            $atendimento = $dados["atendimento"];
            $curva = $dados["curva"];
            $diag_prog = $dados["diag_prog"];
            $medidas = $dados["medidas"];
            $mobilidade_articular = $dados["mobilidade_articular"];
            $plano_frontal = $dados["plano_frontal"];
            $plano_horizontal_milimetros = $dados["plano_horizontal_milimetros"];
            $plano_horizontal_graus = $dados["plano_horizontal_graus"];
            $plano_sagital = $dados["plano_sagital"];
            $vertebra_apice = $dados["vertebra_apice"];
            $vertebra_limite = $dados["vertebra_limite"];

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

            // Medidas

            $novoPlanoFrontal = null;
            $novoPlanoHorizontalMilimetros = null;
            $novoPlanoHorizontalGraus = null;
            $novoPlanoSagital = null;
            $novoMobilidadeArticular = null;
            $salvarMedidas = false;

            if(!empty($plano_frontal)) {
                $novoPlanoFrontal = new PlanoFrontal($plano_frontal);
                $salvarMedidas = true;
            }

            if(!empty($plano_horizontal_milimetros)) {
                $novoPlanoHorizontalMilimetros = new PlanoHorizontalMilimetros($plano_horizontal_milimetros);
                $salvarMedidas = true;
            }

            if(!empty($plano_horizontal_graus)) {
                $novoPlanoHorizontalGraus = new PlanoHorizontalGraus($plano_horizontal_graus);
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

                if(!empty($plano_horizontal_milimetros))
                    $novoMedidas->plano_horizontal_milimetros()->save($novoPlanoHorizontalMilimetros);

                if(!empty($plano_horizontal_graus))
                    $novoMedidas->plano_horizontal_graus()->save($novoPlanoHorizontalGraus);

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
            $novoVertebraApice = null;
            $novoVertebraLimite = null;
            $salvarDiagProg = false;

            if(!empty($curva))
                $salvarDiagProg = true;

            if(!empty($vertebra_apice)) {
                $novoVertebraApice = new VertebraApice($vertebra_apice);
                $salvarDiagProg = true;
            }

            if(!empty($vertebra_limite)) {
                $novoVertebraLimite = new VertebraLimite($vertebra_limite);
                $salvarDiagProg = true;
            }

            if($salvarDiagProg) {
                $novoDiagProg = new DiagnosticoPrognostico($diag_prog);
                $novoAtendimento->diag_prog()->save($novoDiagProg);

                if(!empty($curva)) {
                    if(!empty($curva["curva1"]))
                        $novoDiagProg->curva()->save(new Curva($curva["curva1"]));

                    if(!empty($curva["curva2"]))
                        $novoDiagProg->curva()->save(new Curva($curva["curva2"]));

                    if(!empty($curva["curva3"]))
                        $novoDiagProg->curva()->save(new Curva($curva["curva3"]));

                    if(!empty($curva["curva4"]))
                        $novoDiagProg->curva()->save(new Curva($curva["curva4"]));
                }

                if(!empty($vertebra_apice))
                    $novoDiagProg->vertebra_apice()->save($novoVertebraApice);

                if(!empty($vertebra_limite))
                    $novoDiagProg->vertebra_limite()->save($novoVertebraLimite);
            }
            else if (!empty($diag_prog)){
                $novoDiagProg = new DiagnosticoPrognostico($diag_prog);
                $novoAtendimento->diag_prog()->save($novoDiagProg);
            }
        });
    }

    public function getAtendimentos($id, $offset)
    {
        $paciente = Paciente::where('id', $id)->first();

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
                    array_push($atendimentosNums, $i);
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

                $plano_horizontal_milimetros = $medidas->plano_horizontal_milimetros();
                if($plano_horizontal_milimetros->count() == 1)
                    $var->plano_horizontal_milimetros = $plano_horizontal_milimetros->getResults();

                $plano_horizontal_graus = $medidas->plano_horizontal_graus();
                if($plano_horizontal_graus->count() == 1)
                    $var->plano_horizontal_graus = $plano_horizontal_graus->getResults();

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
                if($curva->count() >= 1)
                    $var->curva = $curva->getResults();

                $vertebra_apice = $diag_prog->vertebra_apice();
                if($vertebra_apice->count() == 1)
                    $var->vertebra_apice = $vertebra_apice->getResults();

                $vertebra_limite = $diag_prog->vertebra_limite();
                if($vertebra_limite->count() == 1)
                    $var->vertebra_limite = $vertebra_limite->getResults();
            }

            array_push($atendimentosResponse, $var);
        }

        return ['quantidade' => $atendCount, 'atendimentos' => $atendimentosResponse, 'atendimentosNums' => $atendimentosNums];
    }

    public function getIdadeAparecimento($id)
    {
        $paciente = Paciente::where('id', $id)->first();
        $qtd_atend = $paciente->atendimento()->count();

        if($qtd_atend == 0)
            return "";
        else {
            $lastAtend = Atendimento::where('paciente_id', $id)->orderBy('id', 'DESC')->first();
            $diag_prog = $lastAtend->diag_prog();

            if($diag_prog->count() == 1 && $diag_prog->getResults()->idade_aparecimento != null) {
                return $diag_prog->getResults()->idade_aparecimento;
            }
            else {
                return "";
            }
        } 
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
            $t = microtime(true);
            $micro = sprintf("%06d",($t - floor($t)) * 1000000);
            $d = new DateTime( date('Y-m-d H:i:s.' . $micro, $t) );
            $chars = array(".", ":");

            $foto->storeAs('public/foto_atendimento/' . $nome . " - " . $cpf . "/" . $num_atend, str_replace($chars, "-", $d->format("Y-m-d H:i:s.u")) . "." . $foto->getClientOriginalExtension());
        }
    }

    public function listarFotos($nome, $cpf, $num, $cpfUsuario)
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

        $funcao = DB::table('usuario')->select('funcao')->where('cpf', $cpfUsuario)->first();

        return ['fotos' => $fotos, 'funcao' => $funcao->funcao];
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

    public function deletarFotos(Request $request)
    {
        $fotos = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);

        foreach($fotos as $foto) {
            Storage::delete('public/' . explode("/", $foto["url"], 3)[2]);
        }

        $explode_nome_foto = explode("/", $foto["url"]);
        $nome_foto = end($explode_nome_foto);

        $pastaAtend = str_replace("/" . $nome_foto, "", 'public/' . explode("/", $foto["url"], 3)[2]);

        if(count(Storage::allFiles($pastaAtend)) == 0)
            Storage::deleteDirectory($pastaAtend);

        $pastaPaciente = substr($pastaAtend, 0, -4);

        if(count(Storage::directories($pastaPaciente)) == 0)
            Storage::deleteDirectory($pastaPaciente);

        return $pastaAtend;
    }
}