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

class AtendimentoApiController extends Controller
{
    public function addAtendimento($cpf, Request $request)
    { 
        $dados = sizeof($_POST) > 0 ? $_POST : json_decode($request->getContent(), true);
        $paciente = Paciente::where('cpf', $cpf)->first();
    }
}