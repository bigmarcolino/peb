<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return view('usuario.auth.login');
});

Route::group(['prefix' => 'usuario'], function () {
  Route::get('/login', 'UsuarioAuth\LoginController@showLoginForm');
  Route::post('/login', 'UsuarioAuth\LoginController@login');
  Route::post('/logout', 'UsuarioAuth\LoginController@logout');

  Route::get('/register', 'UsuarioAuth\RegisterController@showRegistrationForm');
  Route::post('/register', 'UsuarioAuth\RegisterController@register');

  Route::post('/password/email', 'UsuarioAuth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('/password/reset', 'UsuarioAuth\ResetPasswordController@reset');
  Route::get('/password/reset', 'UsuarioAuth\ForgotPasswordController@showLinkRequestForm');
  Route::get('/password/reset/{token}', 'UsuarioAuth\ResetPasswordController@showResetForm');

  Route::get('/listarUsuariosPacientes', 'UsuarioApi\UsuarioApiController@listarUsuariosPacientes');
  Route::get('/qtdUsuariosInativos', 'UsuarioApi\UsuarioApiController@qtdUsuariosInativos');
  Route::post('/excluirUsuarios', 'UsuarioApi\UsuarioApiController@excluirUsuarios');
  Route::put('/editarUsuario', 'UsuarioApi\UsuarioApiController@editarUsuario');
  Route::get('/usuarioLogado/{cpf}', 'UsuarioApi\UsuarioApiController@usuarioLogado');

  Route::post('/addPaciente', 'PacienteApi\PacienteApiController@addPaciente');
  Route::post('/excluirPacientes', 'PacienteApi\PacienteApiController@excluirPacientes');
  Route::get('/checkExistenciaCpfPaciente/{cpf}', 'PacienteApi\PacienteApiController@checkExistenciaCpfPaciente');
  Route::get('/checkExistenciaCpfResponsavel/{cpf}', 'PacienteApi\PacienteApiController@checkExistenciaCpfResponsavel');
  Route::get('/getPacienteEdit/{id}', 'PacienteApi\PacienteApiController@getPacienteEdit');
  Route::put('/editarPaciente', 'PacienteApi\PacienteApiController@editarPaciente');

  Route::post('/addAtendimento/{id}', 'AtendimentoApi\AtendimentoApiController@addAtendimento');
  Route::get('/getAtendimentos/{id}/{offset}', 'AtendimentoApi\AtendimentoApiController@getAtendimentos');
  Route::get('/getIdadeAparecimento/{id}', 'AtendimentoApi\AtendimentoApiController@getIdadeAparecimento');
  Route::post('/uploadFotos/{nome}/{cpf}/{num}', 'AtendimentoApi\AtendimentoApiController@uploadFotos');
  Route::get('/listarFotos/{nome}/{cpf}/{num}/{cpfUsuario}', 'AtendimentoApi\AtendimentoApiController@listarFotos');
  Route::get('/getQtdFotosAtend/{nome}/{cpf}/{num}', 'AtendimentoApi\AtendimentoApiController@getQtdFotosAtend');
  Route::post('/deletarFotos', 'AtendimentoApi\AtendimentoApiController@deletarFotos');
});
