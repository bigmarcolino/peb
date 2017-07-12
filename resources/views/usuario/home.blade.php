@extends('usuario.layout.auth')

@section('content')

<div class="container-fluid main-container" ng-init="qtdUsuariosInativos(); listarUsuariosPacientes()">


	<!-- ----- Início Tabela de Usuários ------- -->
	<div ng-if="showUsuarios" class="tabela-containers">
		<h2 class="title-lg">Usuários</h2>

		<form action="" class="p-b-s form-filter-patients form-desktop form-search-input">
			<div class="form-group input-group-lg has-feedback">
				<input type="text" class="form-control" placeholder="Digite o nome, email, CPF ..." ng-model="searchUser" ng-change="filtrarUsuarios()">
				<span class="glyphicon glyphicon-search form-control-feedback"></span>
			</div>
		</form>

		<div class="form-patients p-s bg-white bd bd-gray bd-radius content-height-2">	
			<div class="pull-left form-group" ng-if="!showSpinnerUsuarios">
				<div class="dropdown btn-group btn-group-sm">
					<button id="dropdown-delete" data-toggle="dropdown" role="button" class="dropdown-toggle btn btn-sm btn-default" aria-haspopup="true" aria-expanded="false" type="button">
						<span class="glyphicon glyphicon-check"></span>
						<span>
							<span> </span>
							<span class="caret"></span>
						</span>
					</button>

					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdown-delete">
						<li role="presentation" class="dropdown-delete-sln" data-toggle="modal" data-target="#modalExclusao">
							<a role="menuitem" href="" tabindex="-1">Excluir Selecionados</a>
						</li>
					</ul>
				</div>
			</div>

			<table class="table table-default table-list table-list-patients" ng-if="!showSpinnerUsuarios">
			    <thead ng-if="usuariosFiltrados.length > 0">
			      	<tr>
			      		<th class="col-checkbox">
				            <label class="checkbox-default">
				            	<input type="checkbox" ng-checked="checkboxSelecionarUsuarios" ng-click="selecionarTodosUsuarios()">
				            	<span></span>
				            </label> 
				        </th>

				        <th ng-click="ordenarUsuarios('name')" class="semi-bold col-lg-4 col-md-3">
				            <span>Nome</span> 
				        	<span ng-show="sortTypeUser == 'name' && !sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				            <span ng-show="sortTypeUser == 'name' && sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				        </th>

				        <th ng-click="ordenarUsuarios('email')" class="semi-bold col-lg-2 col-md-2 hidden-xs">
				          	<span>Email</span>
				            <span ng-show="sortTypeUser == 'email' && !sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				            <span ng-show="sortTypeUser == 'email' && sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				        </th>

				        <th ng-click="ordenarUsuarios('cpf')" class="semi-bold col-lg-1 hidden-sm hidden-xs">
				          	<span>Cpf</span> 
				            <span ng-show="sortTypeUser == 'cpf' && !sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				            <span ng-show="sortTypeUser == 'cpf' && sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				        </th>

				        <th ng-click="ordenarUsuarios('data_nasc')" class="semi-bold hidden-sm hidden-xs">
				          	<span>Data de Nascimento</span>
				            <span ng-show="sortTypeUser == 'data_nasc' && !sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				            <span ng-show="sortTypeUser == 'data_nasc' && sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				        </th>

				        <th ng-click="ordenarUsuarios('sexo')" class="semi-bold col-lg-1 hidden-sm hidden-xs">
				          	<span>Sexo</span>
				            <span ng-show="sortTypeUser == 'sexo' && !sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				            <span ng-show="sortTypeUser == 'sexo' && sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				        </th>

				        <th ng-click="ordenarUsuarios('funcao')" class="semi-bold col-lg-1 hidden-sm hidden-xs">
				          	<span>Função</span>
				            <span ng-show="sortTypeUser == 'funcao' && !sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				            <span ng-show="sortTypeUser == 'funcao' && sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				        </th>

				        <th class="semi-bold col-edit"></th>
			      	</tr>
			    </thead>
			    
			    <tbody>
				    <tr ng-repeat="usuario in usuariosFiltrados | limitTo: pagerObjectUsuarios.currentPage*pageSizeUsuarios | limitTo: pageSizeUsuarios*(-1)">
				        <td>
				        	<label class="checkbox-default" ng-if="cpfLogged() != usuario.cpf">
				            	<input type="checkbox" ng-model="usuario.checked">
				            	<span></span>
				            </label>
				        </td>
				        <td>
				        	[[ usuario.name ]]
				        	<span ng-if="cpfLogged() == usuario.cpf">
				        		(Você)
				        	</span>
				        </td>
				        <td class="hidden-xs">[[ usuario.email ]]</td>
				        <td class="hidden-sm hidden-xs">[[ usuario.cpf ]]</td>
				        <td class="hidden-sm hidden-xs">[[ usuario.data_nasc | dateBr]]</td>
				        <td class="hidden-sm hidden-xs">[[ usuario.sexo ]]</td>
				        <td class="hidden-sm hidden-xs">[[ usuario.funcao | tracos ]]</td>
				        <td class="text-right">
				        	<a href="" class="btn btn-white-blue btn-xs edit-patient-button" tooltips tooltip-template="Editar" tooltip-side="left" data-toggle="modal" data-target="#modalEditarUsuarios" ng-click="setUsuarioEdit(usuario)">
				        		<span class="glyphicon glyphicon-pencil"></span>
				        	</a>
				        </td>
				    </tr>
			    </tbody>
			</table>

			<div class="m-t-es alert alert-warning" ng-if="usuariosFiltrados.length == 0">Nenhum registro encontrado</div>

			<span us-spinner="{radius:30, width:8, length: 16, color: '#2c97d1'}" spinner-on="showSpinnerUsuarios"></span>

			<div class="total-count text-small" ng-if="!showSpinnerUsuarios">
				<span>[[ usuariosFiltrados.length ]]</span>
				<span> resultado</span><span ng-if="usuariosFiltrados.length != 1">s</span>
			</div>

			<ul ng-if="usuariosFiltrados.length > pageSizeUsuarios" class="pagination">
	            <li ng-if="pagerObjectUsuarios.currentPage !== 1">
	                <a ng-click="atualizarPagerUsuarios(1)"><<</a>
	            </li>
	            <li ng-if="pagerObjectUsuarios.currentPage !== 1">
	                <a ng-click="atualizarPagerUsuarios(pagerObjectUsuarios.currentPage - 1)"><</a>
	            </li>
	            <li ng-repeat="page in pagerObjectUsuarios.pages" ng-class="{active: pagerObjectUsuarios.currentPage === page}">
	                <a ng-click="atualizarPagerUsuarios(page)">[[page]]</a>
	            </li>               
	            <li ng-if="pagerObjectUsuarios.currentPage !== pagerObjectUsuarios.totalPages">
	                <a ng-click="atualizarPagerUsuarios(pagerObjectUsuarios.currentPage + 1)">></a>
	            </li>
	            <li ng-if="pagerObjectUsuarios.currentPage !== pagerObjectUsuarios.totalPages">
	                <a ng-click="atualizarPagerUsuarios(pagerObjectUsuarios.totalPages)">>></a>
	            </li>
	        </ul>
		</div>
	</div>

	<!-- Modals Usuários -->

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalExclusao">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Atenção</h4>
	      		</div>

		      	<div class="modal-body">
			        Deseja excluir os usuários selecionados?
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Não</button>
			        <button type="button" class="btn btn-red upper btn-loading confirm-remove-btn btn-loading" data-dismiss="modal" ng-click="excluirUsuarios()">Sim</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalErroExcluir">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Excluindo...</h4>
	      		</div>

		      	<div class="modal-body">
		      		<span ng-if="!showSpinnerExcluir">
		      			Erro ao excluir todos os usuários. Verifique sua conexão com a internet e tente novamente.
		      		</span>

		      		<div style="height: 25px">
		      			<span us-spinner="{radius:10, width:4, length: 8, color: '#2c97d1'}" spinner-on="showSpinnerExcluir"></span>
		      		</div>
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalErroUsuarios">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Carregando...</h4>
	      		</div>

		      	<div class="modal-body">
		      		Erro ao carregar usuários e pacientes. Verifique sua conexão com a internet e tente novamente.
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalErroUsuarios">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Carregando...</h4>
	      		</div>

		      	<div class="modal-body">
		      		Erro ao carregar usuários. Verifique sua conexão com a internet e tente novamente.
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalEditarUsuarios" data-backdrop="static">
	  	<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Editar usuário</h4>
	      		</div>

		      	<div class="modal-body">
		      		<form class="form-horizontal">
	                    <div class="form-group" ng-class="{'has-error': nomeVazioEditarUsuario}">
	                        <input id="name" type="text" class="form-control" name="name" placeholder="Nome:" ng-model="usuarioEdit.name" ng-change="checkNomeEditarUsuario()" autofocus>

	                        <span class="help-block" ng-if="nomeVazioEditarUsuario">
                                <strong>O campo nome é obrigatório</strong>
                            </span>
	                    </div>

	                    <div class="form-group" ng-class="{'has-error': emailVazioEditarUsuario || emailExisteEditarUsuario}">
	                        <input id="email" type="email" class="form-control" name="email" placeholder="Email:" ng-model="usuarioEdit.email" ng-change="checkEmailEditarUsuario(); checkEmailExistenciaEditarUsuario()">

	                        <span class="help-block" ng-if="emailExisteEditarUsuario">
                                <strong>O email já existe</strong>
                            </span>

                            <span class="help-block" ng-if="emailVazioEditarUsuario">
                                <strong>O campo email é obrigatório</strong>
                            </span>
	                    </div>

	                    <div class="form-group">
	                        <select id="sexo" class="form-control" name="sexo" ng-model="usuarioEdit.sexo">
	                            <option ng-selected="usuarioEdit.sexo == 'Masculino'">Masculino</option>
	                            <option ng-selected="usuarioEdit.sexo == 'Feminino'">Feminino</option>
	                        </select>
	                    </div>

	                    <div class="form-group">
	                    	<div class="input-group">
		                    	<input class="form-control" type="text" name="data_nasc" placeholder="Data de Nascimento:" ng-model="usuarioEdit.data_nasc" options="dpEditarUsuarioOptions" datetimepicker readonly>
							    <span class="input-group-addon" id="data_nasc">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </span>
							</div>
	                    </div>

	                    <div class="form-group" ng-if="cpfLogged() != usuarioEdit.cpf">
	                        <select id="funcao" class="form-control" name="funcao" ng-model="usuarioEdit.funcao">
	                        	<option ng-selected="usuarioEdit.funcao == ''"></option>
	                            <option ng-selected="usuarioEdit.funcao == 'Admin'">Admin</option>
	                            <option ng-selected="usuarioEdit.funcao == 'Analista'">Analista</option>
	                            <option ng-selected="usuarioEdit.funcao == 'Examinador'">Examinador</option>
	                        </select>
	                    </div>
	                </form>
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			        <button type="button" class="btn btn-red upper btn-loading confirm-remove-btn btn-loading" data-dismiss="modal" ng-click="salvarEdicaoUsuario()" ng-disabled="emailExisteEditarUsuario || emailVazioEditarUsuario || nomeVazioEditarUsuario">Salvar</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalErroEditarUsuario">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Salvando edição...</h4>
	      		</div>

		      	<div class="modal-body">
		      		<span ng-if="!showSpinnerEditarUsuario">
		      			Erro ao editar o usuário. Verifique sua conexão com a internet e tente novamente.
		      		</span>

		      		<div style="height: 25px">
		      			<span us-spinner="{radius:10, width:4, length: 8, color: '#2c97d1'}" spinner-on="showSpinnerEditarUsuario"></span>
		      		</div>
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
	  	</div>
	</div>
	<!-- ----- Fim Tabela de Usuários ------- -->



	<!-- ----- Início Tabela de Pacientes ------- -->
	<div ng-if="showPacientes" class="tabela-containers">
		<h2 class="title-lg">Pacientes</h2>

		<form action="" class="p-b-s form-filter-patients form-desktop form-search-input">
			<div class="form-group input-group-lg has-feedback">
				<input type="text" class="form-control" placeholder="Digite o nome, email, CPF ..." ng-model="searchPaciente" ng-change="filtrarPacientes()">
				<span class="glyphicon glyphicon-search form-control-feedback"></span>
			</div>
		</form>

		<div class="form-patients p-s bg-white bd bd-gray bd-radius content-height-2">
			@if (Auth::user()->funcao != "Analista")
				<div class="pull-right" ng-click="togglePaginas('addPacientes')" ng-if="!showSpinnerUsuarios">
					<a class="btn btn-green btn-sm">ADICIONAR</a>
				</div>
			@endif

			<div class="pull-left form-group" ng-if="!showSpinnerUsuarios">
				<div class="dropdown btn-group btn-group-sm">
					<button id="dropdown-delete" data-toggle="dropdown" role="button" class="dropdown-toggle btn btn-sm btn-default" aria-haspopup="true" aria-expanded="false" type="button">
						<span class="glyphicon glyphicon-check"></span>
						<span>
							<span> </span>
							<span class="caret"></span>
						</span>
					</button>

					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdown-delete">
						<li role="presentation" class="dropdown-delete-sln" data-toggle="modal" data-target="#modalExclusao">
							<a role="menuitem" href="" tabindex="-1">Excluir Selecionados</a>
						</li>
					</ul>
				</div>
			</div>

			<table class="table table-default table-list table-list-patients" ng-if="!showSpinnerUsuarios">
			    <thead ng-if="pacientesFiltrados.length > 0">
			      	<tr>
			      		<th class="col-checkbox">
				            <label class="checkbox-default">
				            	<input type="checkbox" ng-checked="checkboxSelecionarPacientes" ng-click="selecionarTodosPacientes()">
				            	<span></span>
				            </label> 
				        </th>

				        <th ng-click="ordenarPacientes('nome')" class="semi-bold col-lg-4 col-md-3">
				            <span>Nome</span> 
				        	<span ng-show="sortTypePaciente == 'nome' && !sortReversePaciente" class="glyphicon glyphicon-chevron-up"></span>
				            <span ng-show="sortTypePaciente == 'nome' && sortReversePaciente" class="glyphicon glyphicon-chevron-down"></span>
				        </th>

				        <th ng-click="ordenarPacientes('email')" class="semi-bold col-lg-2 col-md-2 hidden-xs">
				          	<span>Email</span>
				            <span ng-show="sortTypePaciente == 'email' && !sortReversePaciente" class="glyphicon glyphicon-chevron-up"></span>
				            <span ng-show="sortTypePaciente == 'email' && sortReversePaciente" class="glyphicon glyphicon-chevron-down"></span>
				        </th>

				        <th ng-click="ordenarPacientes('cpf')" class="semi-bold col-lg-1 hidden-sm hidden-xs">
				          	<span>Cpf</span> 
				            <span ng-show="sortTypePaciente == 'cpf' && !sortReversePaciente" class="glyphicon glyphicon-chevron-up"></span>
				            <span ng-show="sortTypePaciente == 'cpf' && sortReversePaciente" class="glyphicon glyphicon-chevron-down"></span>
				        </th>

				        <th ng-click="ordenarPacientes('data_nasc')" class="semi-bold hidden-sm hidden-xs">
				          	<span>Data de Nascimento</span>
				            <span ng-show="sortTypePaciente == 'data_nasc' && !sortReversePaciente" class="glyphicon glyphicon-chevron-up"></span>
				            <span ng-show="sortTypePaciente == 'data_nasc' && sortReversePaciente" class="glyphicon glyphicon-chevron-down"></span>
				        </th>

				        <th class="semi-bold col-edit"></th>
			      	</tr>
			    </thead>
			    
			    <tbody>
				    <tr ng-repeat="paciente in pacientesFiltrados | limitTo: pagerObjectPacientes.currentPage*pageSizePacientes | limitTo: pageSizePacientes*(-1)">
				        <td>
				        	<label class="checkbox-default">
				            	<input type="checkbox" ng-model="paciente.checked">
				            	<span></span>
				            </label>
				        </td>
				        <td>[[ paciente.nome ]]</td>
				        <td class="hidden-xs">[[ paciente.email ]]</td>
				        <td class="hidden-sm hidden-xs">[[ paciente.cpf ]]</td>
				        <td class="hidden-sm hidden-xs">[[ paciente.data_nasc | dateBr]]</td>
				        <td class="text-right">
				        	<a href="" class="btn btn-white-blue btn-xs edit-patient-button" tooltips tooltip-template="Editar" tooltip-side="left" data-toggle="modal" data-target="#modalEditarUsuarios" ng-click="">
				        		<span class="glyphicon glyphicon-pencil"></span>
				        	</a>
				        </td>
				    </tr>
			    </tbody>
			</table>

			<div class="m-t-es alert alert-warning" ng-if="pacientesFiltrados.length == 0">Nenhum registro encontrado</div>

			<span us-spinner="{radius:30, width:8, length: 16, color: '#2c97d1'}" spinner-on="showSpinnerUsuarios"></span>

			<div class="total-count text-small" ng-if="showSpinnerUsuarios">
				<span>[[ pacientesFiltrados.length ]]</span>
				<span> resultado</span><span ng-if="pacientesFiltrados.length != 1">s</span>
			</div>

			<ul ng-if="pacientesFiltrados.length > pageSizePacientes" class="pagination">
	            <li ng-if="pagerObjectPacientes.currentPage !== 1">
	                <a ng-click="atualizarPagerPacientes(1)"><<</a>
	            </li>
	            <li ng-if="pagerObjectPacientes.currentPage !== 1">
	                <a ng-click="atualizarPagerPacientes(pagerObjectPacientes.currentPage - 1)"><</a>
	            </li>
	            <li ng-repeat="page in pagerObjectPacientes.pages" ng-class="{active: pagerObjectPacientes.currentPage === page}">
	                <a ng-click="atualizarPagerPacientes(page)">[[page]]</a>
	            </li>               
	            <li ng-if="pagerObjectPacientes.currentPage !== pagerObjectPacientes.totalPages">
	                <a ng-click="atualizarPagerPacientes(pagerObjectPacientes.currentPage + 1)">></a>
	            </li>
	            <li ng-if="pagerObjectPacientes.currentPage !== pagerObjectPacientes.totalPages">
	                <a ng-click="atualizarPagerPacientes(pagerObjectPacientes.totalPages)">>></a>
	            </li>
	        </ul>
		</div>
	</div>
	<!-- ----- Fim Tabela de Pacientes ------- -->



	<!-- ----- Início Adição de Pacientes ------- -->
	<div ng-if="showAddPacientes" class="peb-containers">
		<h2 class="title-lg">Adicionar Pacientes</h2>

		<div class="form-patients-update m-b-s bg-white bd bd-gray bd-radius content-settings form-horizontal form-label tab-content" id="patient-update-form">
			<div class="tab-pane fade in active tab-main-info" id="main-info">
                <div class="p-s container-form">
                    <h3 class="title-form text-medium semi-bold p-b-s c-ic-blue upper">Geral</h3>

                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Nome*</label>
                            <div class="col-sm-3" ng-class="{'has-error': nomeVazioAddPaciente}">
                                <input type="text" name="name" class="form-control" ng-model="novoPaciente.nome" ng-change="checkNomeAddPaciente()">

                                <span class="help-block" ng-if="nomeVazioAddPaciente">
	                                <strong>O campo nome é obrigatório</strong>
	                            </span>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">CPF*</label>
                            <div class="col-sm-2" id="patient-code-gen" ng-class="{'has-error': cpfVazioAddPaciente}">
                                <input id="id_patient_code" type="text" name="patient_code" class="form-control" ng-model="novoPaciente.cpf" numbers-only ng-change="checkCpfAddPaciente()">

                                <span class="help-block" ng-if="cpfVazioAddPaciente">
	                                <strong>O campo CPF é obrigatório</strong>
	                            </span>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Identidade</label>
                            <div class="col-sm-1" id="patient-code-gen">
                                <input id="id_patient_code type="text" name="patient_code" class="form-control" ng-model="novoPaciente.identidade" numbers-only>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-sm form-birthday">
                    	<div>
	                        <label class="col-sm-2 control-label">Data de nascimento*</label>
	                        <div class="col-sm-3" ng-class="{'has-error': dataVazioAddPaciente}">
	                        	<div class="input-group">
		                            <input class="form-control" id="birth-date-field" name="birth_date" type="text" ng-model="novoPaciente.data_nasc" options="dpNovoPacienteOptions" datetimepicker readonly ng-change="checkDataAddPaciente()">

		                            <span class="input-group-addon pointer">
		                                <span class="glyphicon glyphicon-calendar"></span>
		                            </span>
		                        </div>

		                        <span class="help-block" ng-if="dataVazioAddPaciente">
	                                <strong>O campo Data de nascimento é obrigatório</strong>
	                            </span>
	                        </div>
	                    </div>

	                    <div>
	                    	<label class="col-sm-1 control-label">E-mail</label>
	                        <div class="col-sm-3">
	                            <input class="form-control" id="id_email" name="email" type="email" ng-model="novoPaciente.email">
	                        </div>
	                    </div>
                    </div>
                   
                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Médico</label>
                            <div class="col-sm-2">
                                <input type="text" name="home_phone" class="form-control" ng-model="novoPaciente.medico">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Indicação</label>
                            <div class="col-sm-2">
                                <input class="form-control" id="id_office_phone" name="office_phone" type="text" ng-model="novoPaciente.indicacao">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-sm"></div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper">Telefones</h3>

                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Celular</label>
                            <div class="col-sm-2">
                                <input type="text" name="mobile_phone" class="form-control" ng-model="novoPaciente.celular" numbers-only>
                            </div>
                        </div>

                        <div>
                            <label class="col-sm-1 control-label">Casa</label>
                            <div class="col-sm-2">
                                <input type="text" name="home_phone" class="form-control" ng-model="novoPaciente.tel_res" numbers-only>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Trabalho</label>
                            <div class="col-sm-2">
                                <input class="form-control" id="id_office_phone" name="office_phone" type="text" ng-model="novoPaciente.tel_trab" numbers-only>
                            </div>
                        </div>
                    </div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper">Endereço</h3>

                    <div data-zipcode="context">
                        <div class="form-group form-group-sm">
                        	<div>
                                <label class="col-sm-2 control-label">Endereço</label>
                                <div class="col-sm-4">
                                    <input class="form-control" id="id_address" name="address" type="text" ng-model="novoPaciente.end_res">
                                </div>
                            </div>

                            <div>
                                <label class="col-sm-1 control-label">CEP</label>
                                <div class="col-sm-2">
                                    <input class="form-control" id="id_zip_code" name="zip_code" type="text" ng-model="novoPaciente.cep" numbers-only>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-sm">
                            <div>
                                <label class="col-sm-2 control-label">Cidade</label>
                                <div class="col-sm-3">
                                    <input class="form-control" id="id_city" name="city" type="text" ng-model="novoPaciente.cidade">
                                </div>
                            </div>
                            <div>
                                <label class="col-sm-1 control-label">Estado</label>

                                <div class="col-sm-2">
	                              	<select class="select-picker form-control" id="id_state" name="state" ng-model="novoPaciente.estado">
										<option value="" selected="selected"></option>
										<option value="AC">Acre</option>
										<option value="AL">Alagoas</option>
										<option value="AP">Amapá</option>
										<option value="AM">Amazonas</option>
										<option value="BA">Bahia</option>
										<option value="CE">Ceará</option>
										<option value="DF">Distrito Federal</option>
										<option value="ES">Espírito Santo</option>
										<option value="GO">Goiás</option>
										<option value="MA">Maranhão</option>
										<option value="MT">Mato Grosso</option>
										<option value="MS">Mato Grosso do Sul</option>
										<option value="MG">Minas Gerais</option>
										<option value="PA">Pará</option>
										<option value="PB">Paraíba</option>
										<option value="PR">Paraná</option>
										<option value="PE">Pernambuco</option>
										<option value="PI">Piauí</option>
										<option value="RJ">Rio de Janeiro</option>
										<option value="RN">Rio Grande do Norte</option>
										<option value="RS">Rio Grande do Sul</option>
										<option value="RO">Rondônia</option>
										<option value="RR">Roraima</option>
										<option value="SC">Santa Catarina</option>
										<option value="SP">São Paulo</option>
										<option value="SE">Sergipe</option>
										<option value="TO">Tocantins</option>
									</select>
                        		</div>
                   			</div>
                		</div>
                	</div>
                </div>

				<div class="bg-white-light p-s text-right bd-t bd-gray clearfix submit-row">
				    <div class="clearfix pull-right">
				        <button type="button" class="btn btn-green upper pull-right btn-loading" id="form-submit-button" ng-click="addPaciente()" ng-disabled="dataVazioAddPaciente || nomeVazioAddPaciente || cpfVazioAddPaciente">
				        	<span class="btn-loading-text">Salvar</span>			            	
			            </button>

			            <button type="button" class="btn btn-default upper m-r-es pull-left btn-loading" ng-click="addPaciente(true)" ng-disabled="dataVazioAddPaciente || nomeVazioAddPaciente || cpfVazioAddPaciente">
			            	<span class="btn-loading-text">Salvar e adicionar outro</span>
			            </button>	
				    </div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalAddPaciente">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Adicionando...</h4>
	      		</div>

		      	<div class="modal-body">
		      		<span ng-if="!showSpinnerAddPaciente">
		      			Erro ao adicionar o paciente. Verifique sua conexão com a internet e tente novamente.
		      		</span>

		      		<div style="height: 25px">
		      			<span us-spinner="{radius:10, width:4, length: 8, color: '#2c97d1'}" spinner-on="showSpinnerAddPaciente"></span>
		      		</div>
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
	  	</div>
	</div>
	<!-- ----- Fim Adição de Pacientes ------- -->

</div>

@endsection
