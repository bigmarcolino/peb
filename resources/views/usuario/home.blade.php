@extends('usuario.layout.auth')

@section('content')

<div class="ic-sidebar" ng-if="showViewPacientes && !showSpinnerGetAtendimento">
    <div class="sidebar-records">
    	<div class="ps-container ps-theme-default ps-active-y">
    		<h1 class="module-title bd-b bd-gray">Prontuários</h1>

    		<div class="time-visit p-s">
    			<div class="collapse in">
    				<span class="btn btn-block btn-green text-medium m-t-es btn-loading" ng-if="showIniciarAtendimento" ng-click="toggleButtonAtendimento()">
    					<span>
    						<span>
    							<span class="text-small glyphicon glyphicon-play"></span>
    							<span></span>
    							<span>INICIAR ATENDIMENTO</span>
    						</span>
    					</span>
    				</span>

    				<span class="btn btn-block btn-red text-medium m-t-es btn-loading" data-toggle="modal" data-target="#modalFinalizarAtendimento" ng-if="showFinalizarAtendimento">
    					<span>
    						<span>
    							<span class="text-small glyphicon glyphicon-stop"></span>
    							<span></span>
    							<span>FINALIZAR ATENDIMENTO</span>
    						</span>
    					</span>
    				</span>

    				<span class="link-gray btn-block" data-toggle="modal" data-target="#modalCancelarAtendimento" ng-if="showFinalizarAtendimento">
                        Cancelar atendimento
                    </span>
    			</div>
    		</div>

    		<div class="p-b-s nav-records">
    			<ul class="side-navbar">
    				<li ng-class="{'active' : viewResumo}" ng-click="toggleAtendimento('resumo')">
    					<a href="#" title="Resumo">Resumo</a>
    				</li>

    				<li ng-class="{'active' : viewAtendimento}" ng-click="toggleAtendimento('atendimento')" ng-if="showFinalizarAtendimento">
    					<a href="#" title="Atendimento">Atendimento</a>
    				</li>

    				<li ng-class="{'active' : viewMedidas}" ng-click="toggleAtendimento('medidas')" ng-if="showFinalizarAtendimento">
    					<a href="#" title="Medidas">Medidas</a>
    				</li>

    				<li ng-class="{'active' : viewDiagProg}" ng-click="toggleAtendimento('diagprog')" ng-if="showFinalizarAtendimento">
    					<a href="#" title="Diagnóstico Prognóstico">Diagnóstico Prognóstico</a>
    				</li>
    			</ul>
    		</div>

    		<div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px;">
    			<div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
    		</div>

    		<div class="ps-scrollbar-y-rail" style="top: 0px; height: 138px; right: 3px;">
    			<div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 52px;"></div>
    		</div>
    	</div>
    </div>

	<span class="sidebar-toggle">
        <span class="glyphicon glyphicon-menu-right icon"></span>
    </span>
</div>

<div class="container-fluid main-container" ng-init="qtdUsuariosInativos(); listarUsuariosPacientes()">

	<!-- ----- Início Tabela de Usuários ------- -->
	<div ng-show="showUsuarios" class="tabela-containers">
		<h2 class="title-lg">Usuários</h2>

		<form action="" class="p-b-s form-filter-patients form-desktop form-search-input">
			<div class="form-group input-group-lg has-feedback">
				<input type="text" class="form-control" placeholder="Digite o nome, email, CPF ..." ng-model="searchUser" ng-change="filtrarUsuarios()">
				<span class="glyphicon glyphicon-search form-control-feedback"></span>
			</div>
		</form>

		<div class="form-patients p-s bg-white bd bd-gray bd-radius content-height-2">	
			<div class="pull-left form-group" ng-if="!showSpinnerUsuarios">
				<div class="dropdown btn-group btn-group-sm" ng-if="usuariosFiltrados.length > 1">
					<button id="dropdown-delete" data-toggle="dropdown" role="button" class="dropdown-toggle btn btn-sm btn-default" aria-haspopup="true" aria-expanded="false" type="button">
						<span class="glyphicon glyphicon-check"></span>
						<span>
							<span> </span>
							<span class="caret"></span>
						</span>
					</button>

					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdown-delete">
						<li role="presentation" class="dropdown-delete-sln" data-toggle="modal" data-target="#modalExclusaoUsuarios">
							<a role="menuitem" href="" tabindex="-1">Excluir Selecionados</a>
						</li>
					</ul>
				</div>
			</div>

			<table class="table table-default table-list table-list-patients" ng-if="!showSpinnerUsuarios">
			    <thead ng-if="usuariosFiltrados.length > 0">
			      	<tr>
			      		<th class="col-checkbox" ng-if="usuariosFiltrados.length > 1">
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
				    <tr ng-repeat="usuario in usuariosFiltrados | limitTo: pagerObjectUsuarios.currentPage*pageSizeUsuarios | limitTo: pageSizeUsuarios*(-1) track by $index">
				        <td ng-if="usuariosFiltrados.length > 1">
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
				        	<span class="btn btn-white-blue btn-xs edit-patient-button" tooltips tooltip-template="Editar" tooltip-side="left" data-toggle="modal" data-target="#modalEditarUsuarios" ng-click="setUsuarioEdit(usuario)">
				        		<span class="glyphicon glyphicon-pencil"></span>
				        	</span>
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

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalExclusaoUsuarios">
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
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Cancelar</button>
			        <button type="button" class="btn btn-red upper btn-loading confirm-remove-btn btn-loading" data-dismiss="modal" ng-click="excluirUsuarios()">EXCLUIR</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalErroExcluirUsuarios">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Excluindo...</h4>
	      		</div>

		      	<div class="modal-body">
		      		<span ng-if="!showSpinnerExcluirUsuarios">
		      			Erro ao excluir todos os usuários. Verifique sua conexão com a internet e tente novamente.
		      		</span>

		      		<div style="height: 25px">
		      			<span us-spinner="{radius:10, width:4, length: 8, color: '#2c97d1'}" spinner-on="showSpinnerExcluirUsuarios"></span>
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
			        <button type="button" class="btn btn-red upper btn-loading confirm-remove-btn btn-loading" data-dismiss="modal" ng-click="salvarEdicaoUsuario()" ng-disabled="emailExisteEditarUsuario || emailVazioEditarUsuario || nomeVazioEditarUsuario">SALVAR</button>
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
	<div ng-show="showPacientes" class="tabela-containers">
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

				<div class="pull-left form-group" ng-if="pacientesFiltrados.length > 0">
					<div class="dropdown btn-group btn-group-sm">
						<button id="dropdown-delete" data-toggle="dropdown" role="button" class="dropdown-toggle btn btn-sm btn-default" aria-haspopup="true" aria-expanded="false" type="button">
							<span class="glyphicon glyphicon-check"></span>
							<span>
								<span> </span>
								<span class="caret"></span>
							</span>
						</button>

						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdown-delete">
							<li role="presentation" class="dropdown-delete-sln" data-toggle="modal" data-target="#modalExclusaoPacientes">
								<a role="menuitem" href="" tabindex="-1">Excluir Selecionados</a>
							</li>
						</ul>
					</div>
				</div>
			@endif

			<table class="table table-default table-list table-list-patients" ng-if="!showSpinnerUsuarios">
			    <thead ng-if="pacientesFiltrados.length > 0">
			      	<tr>
			      		@if (Auth::user()->funcao != "Analista")
				      		<th class="col-checkbox">
					            <label class="checkbox-default">
					            	<input type="checkbox" ng-checked="checkboxSelecionarPacientes" ng-click="selecionarTodosPacientes()">
					            	<span></span>
					            </label> 
					        </th>
					    @endif

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

				        @if (Auth::user()->funcao != "Analista")
				        	<th class="semi-bold col-edit"></th>
				        @endif
			      	</tr>
			    </thead>
			    
			    <tbody>
				    <tr ng-repeat="paciente in pacientesFiltrados | limitTo: pagerObjectPacientes.currentPage*pageSizePacientes | limitTo: pageSizePacientes*(-1) track by $index">
				    	@if (Auth::user()->funcao != "Analista")
					        <td>
					        	<label class="checkbox-default">
					            	<input type="checkbox" ng-model="paciente.checked">
					            	<span></span>
					            </label>
					        </td>
					    @endif

				        <td>
				        	<a class="ic-c-blue record-patient-link pointer" ng-click="setViewPaciente(paciente)">
				        		<span>[[ paciente.nome ]]</span>
				        	</a>
				        </td>
				        <td class="hidden-xs">[[ paciente.email | tracos ]]</td>
				        <td class="hidden-sm hidden-xs">[[ paciente.cpf ]]</td>
				        <td class="hidden-sm hidden-xs">[[ paciente.data_nasc | dateBr]]</td>

				        @if (Auth::user()->funcao != "Analista")
					        <td class="text-right">
					        	<span class="btn btn-white-blue btn-xs edit-patient-button" tooltips tooltip-template="Editar" tooltip-side="left" ng-click="setPacienteEdit(paciente)">
					        		<span class="glyphicon glyphicon-pencil"></span>
					        	</span>
					        </td>
					    @endif
				    </tr>
			    </tbody>
			</table>

			<div class="m-t-es alert alert-warning" ng-if="pacientesFiltrados.length == 0">Nenhum registro encontrado</div>

			<span us-spinner="{radius:30, width:8, length: 16, color: '#2c97d1'}" spinner-on="showSpinnerUsuarios"></span>

			<div class="total-count text-small" ng-if="!showSpinnerUsuarios">
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

	<!-- Modals Pacientes -->

	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalExclusaoPacientes">
	  	<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Atenção</h4>
	      		</div>

		      	<div class="modal-body">
		      		Todos os dados relacionados aos pacientes selecionados serão removidos, incluindo agendamentos e atendimentos. Esta operação é irreversível e não poderá ser desfeita. Mesmo assim, tem certeza que deseja excluir os pacientes?
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Cancelar</button>
			        <button type="button" class="btn btn-red upper btn-loading confirm-remove-btn btn-loading" data-dismiss="modal" ng-click="excluirPacientes()">EXCLUIR</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalErroExcluirPacientes">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Excluindo...</h4>
	      		</div>

		      	<div class="modal-body">
		      		<span ng-if="!showSpinnerExcluirPacientes">
		      			Erro ao excluir todos os usuários. Verifique sua conexão com a internet e tente novamente.
		      		</span>

		      		<div style="height: 25px">
		      			<span us-spinner="{radius:10, width:4, length: 8, color: '#2c97d1'}" spinner-on="showSpinnerExcluirPacientes"></span>
		      		</div>
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
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
                            <div class="col-sm-3" ng-class="{'has-error': nomeVazioPaciente}">
                                <input type="text" name="nome" class="form-control" ng-model="novoPaciente.nome" ng-change="checkNomePaciente('add')">

                                <span class="help-block" ng-if="nomeVazioPaciente">
	                                <strong>O campo nome é obrigatório</strong>
	                            </span>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">CPF*</label>
                            <div class="col-sm-2" id="patient-code-gen" ng-class="{'has-error': cpfVazioPaciente || cpfExistePaciente}">
                                <input id="id_patient_code" type="text" name="cpf" class="form-control" ng-model="novoPaciente.cpf" numbers-only ng-change="checkCpfPaciente('add'); checkCpfExistenciaPaciente('add')">

                                <span class="help-block" ng-if="cpfExistePaciente">
	                                <strong>O CPF já existe</strong>
	                            </span>

                                <span class="help-block" ng-if="cpfVazioPaciente">
	                                <strong>O campo CPF é obrigatório</strong>
	                            </span>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Identidade</label>
                            <div class="col-sm-1" id="patient-code-gen">
                                <input id="id_patient_code" type="text" name="identidade" class="form-control" ng-model="novoPaciente.identidade" numbers-only>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-sm form-birthday">
                    	<div>
	                        <label class="col-sm-2 control-label">Data de nascimento*</label>
	                        <div class="col-sm-3" ng-class="{'has-error': dataVazioPaciente}">
	                        	<div class="input-group">
		                            <input class="form-control" id="birth-date-field" name="data_nasc" type="text" ng-model="novoPaciente.data_nasc" options="dpNovoPacienteOptions" datetimepicker readonly ng-change="checkDataPaciente('add')">

		                            <span class="input-group-addon pointer">
		                                <span class="glyphicon glyphicon-calendar"></span>
		                            </span>
		                        </div>

		                        <span class="help-block" ng-if="dataVazioPaciente">
	                                <strong>O campo Data de nascimento é obrigatório</strong>
	                            </span>
	                        </div>
	                    </div>

	                    <div>
	                    	<label class="col-sm-1 control-label">E-mail</label>
	                        <div class="col-sm-3">
	                            <input class="form-control" type="text" name="email" ng-model="novoPaciente.email">
	                        </div>
	                    </div>
                    </div>
                   
                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Médico</label>
                            <div class="col-sm-2">
                                <input type="text" name="medico" class="form-control" ng-model="novoPaciente.medico">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Indicação</label>
                            <div class="col-sm-2">
                                <input class="form-control" id="id_office_phone" name="indicacao" type="text" ng-model="novoPaciente.indicacao">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-sm"></div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper">Telefones</h3>

                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Celular</label>
                            <div class="col-sm-2">
                                <input type="text" name="celular" class="form-control" ng-model="novoPaciente.celular" numbers-only>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Casa</label>
                            <div class="col-sm-2">
                                <input type="text" name="tel_res" class="form-control" ng-model="novoPaciente.tel_res" numbers-only>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Trabalho</label>
                            <div class="col-sm-2">
                                <input class="form-control" id="id_office_phone" name="tel_trab" type="text" ng-model="novoPaciente.tel_trab" numbers-only>
                            </div>
                        </div>
                    </div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper">Endereço</h3>

                    <div data-zipcode="context">
                        <div class="form-group form-group-sm">
                        	<div>
                                <label class="col-sm-2 control-label">Endereço</label>
                                <div class="col-sm-4">
                                    <input class="form-control" id="id_address" name="end_res" type="text" ng-model="novoPaciente.end_res">
                                </div>
                            </div>

                            <div>
                                <label class="col-sm-1 control-label">CEP</label>
                                <div class="col-sm-2">
                                    <input class="form-control" id="id_zip_code" name="cep" type="text" ng-model="novoPaciente.cep" numbers-only>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-sm">
                            <div>
                                <label class="col-sm-2 control-label">Cidade</label>
                                <div class="col-sm-3">
                                    <input class="form-control" id="id_city" name="cidade" type="text" ng-model="novoPaciente.cidade">
                                </div>
                            </div>
                            <div>
                                <label class="col-sm-1 control-label">Estado</label>

                                <div class="col-sm-2">
	                              	<select class="select-picker form-control" id="id_state" name="estado" ng-model="novoPaciente.estado">
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
				        <button type="button" class="btn btn-green upper pull-right btn-loading" id="form-submit-button" ng-click="addPaciente()" ng-disabled="dataVazioPaciente || nomeVazioPaciente || cpfVazioPaciente || cpfExistePaciente">
				        	<span class="btn-loading-text">Salvar</span>			            	
			            </button>

			            <button type="button" class="btn btn-default upper m-r-es pull-left btn-loading" ng-click="addPaciente(true)" ng-disabled="dataVazioPaciente || nomeVazioPaciente || cpfVazioPaciente || cpfExistePaciente">
			            	<span class="btn-loading-text">Salvar e adicionar outro</span>
			            </button>	
				    </div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modals Adição de Pacientes -->

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


	<!-- ----- Início Edição de Pacientes ------- -->
	<div ng-if="showEditPacientes" class="peb-containers">
		<h2 class="title-lg patient-name" ng-if="successLoadPacienteEdit">[[ pacienteEdit.nome | tracos ]]</h2>

		<div class="form-patients-update m-b-s bg-white bd bd-gray bd-radius content-settings form-horizontal form-label tab-content" id="patient-update-form" ng-if="successLoadPacienteEdit">
			<div class="tab-pane fade in active tab-main-info" id="main-info">
                <div class="p-s container-form">
                    <h3 class="title-form text-medium semi-bold p-b-s c-ic-blue upper">Geral</h3>

                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Nome*</label>

                            @if (Auth::user()->funcao != "Analista")
	                            <div class="col-sm-3" ng-class="{'has-error': nomeVazioPaciente}">
	                                <input type="text" name="name" class="form-control" ng-model="pacienteEdit.nome" ng-change="checkNomePaciente('edit')">

	                                <span class="help-block" ng-if="nomeVazioPaciente">
		                                <strong>O campo nome é obrigatório</strong>
		                            </span>
	                            </div>
	                        @else
	                        	<div class="col-sm-3">
	                                <input type="text" name="name" class="form-control" ng-model="pacienteEdit.nome">
	                            </div>
	                        @endif
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Identidade</label>
                            <div class="col-sm-1" id="patient-code-gen">
                                <input id="id_patient_code" type="text" name="patient_code" class="form-control" ng-model="pacienteEdit.identidade" numbers-only>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-sm form-birthday">
                    	<div>
	                        <label class="col-sm-2 control-label">Data de nascimento*</label>
	                        <div class="col-sm-3" ng-class="{'has-error': dataVazioPaciente}">
	                        	<div class="input-group">
		                            <input class="form-control" id="birth-date-field" name="birth_date" type="text" ng-model="pacienteEdit.data_nasc" options="dpNovoPacienteOptions" datetimepicker readonly ng-change="checkDataPaciente('edit')">

		                            <span class="input-group-addon pointer">
		                                <span class="glyphicon glyphicon-calendar"></span>
		                            </span>
		                        </div>

		                        <span class="help-block" ng-if="dataVazioPaciente">
	                                <strong>O campo Data de nascimento é obrigatório</strong>
	                            </span>
	                        </div>
	                    </div>

	                    <div>
	                    	<label class="col-sm-1 control-label">E-mail</label>
	                        <div class="col-sm-3">
	                            <input class="form-control" type="text" ng-model="pacienteEdit.email">
	                        </div>
	                    </div>
                    </div>
                   
                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Médico</label>
                            <div class="col-sm-2">
                                <input type="text" name="home_phone" class="form-control" ng-model="pacienteEdit.medico">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Indicação</label>
                            <div class="col-sm-2">
                                <input class="form-control" id="id_office_phone" name="office_phone" type="text" ng-model="pacienteEdit.indicacao">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-sm"></div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper">Telefones</h3>

                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Celular</label>
                            <div class="col-sm-2">
                                <input type="text" name="mobile_phone" class="form-control" ng-model="pacienteEdit.celular" numbers-only>
                            </div>
                        </div>

                        <div>
                            <label class="col-sm-1 control-label">Casa</label>
                            <div class="col-sm-2">
                                <input type="text" name="home_phone" class="form-control" ng-model="pacienteEdit.tel_res" numbers-only>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Trabalho</label>
                            <div class="col-sm-2">
                                <input class="form-control" id="id_office_phone" name="office_phone" type="text" ng-model="pacienteEdit.tel_trab" numbers-only>
                            </div>
                        </div>
                    </div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper">Endereço</h3>

                    <div data-zipcode="context">
                        <div class="form-group form-group-sm">
                        	<div>
                                <label class="col-sm-2 control-label">Endereço</label>
                                <div class="col-sm-4">
                                    <input class="form-control" id="id_address" name="address" type="text" ng-model="pacienteEdit.end_res">
                                </div>
                            </div>

                            <div>
                                <label class="col-sm-1 control-label">CEP</label>
                                <div class="col-sm-2">
                                    <input class="form-control" id="id_zip_code" name="zip_code" type="text" ng-model="pacienteEdit.cep" numbers-only>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-sm">
                            <div>
                                <label class="col-sm-2 control-label">Cidade</label>
                                <div class="col-sm-3">
                                    <input class="form-control" id="id_city" name="city" type="text" ng-model="pacienteEdit.cidade">
                                </div>
                            </div>
                            <div>
                                <label class="col-sm-1 control-label">Estado</label>

                                <div class="col-sm-2">
	                              	<select class="select-picker form-control" id="id_state" name="state" ng-model="pacienteEdit.estado">
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

                @if (Auth::user()->funcao != "Analista")
					<div class="bg-white-light p-s text-right bd-t bd-gray clearfix submit-row">
					    <div class="clearfix pull-right">
					        <button type="button" class="btn btn-green upper pull-right btn-loading" id="form-submit-button" ng-click="editarPaciente()" ng-disabled="nomeVazioPaciente">
					        	<span class="btn-loading-text">Salvar</span>			            	
				            </button>

				            <button type="button" class="btn btn-default upper m-r-es pull-left btn-loading" ng-click="editarPaciente(true)" ng-disabled="nomeVazioPaciente">
				            	<span class="btn-loading-text">Salvar e continuar editando</span>
				            </button>	
					    </div>
					</div>
				@endif
			</div>
		</div>

		<span us-spinner="{radius:30, width:8, length: 16, color: '#2c97d1'}" spinner-on="showSpinnerLoadPacienteEdit"></span>
	</div>

	<!-- Modals Edição de Pacientes -->

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalEditPaciente">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Editando...</h4>
	      		</div>

		      	<div class="modal-body">
		      		<span ng-if="!showSpinnerEditPaciente">
		      			Erro ao editar o paciente. Verifique sua conexão com a internet e tente novamente.
		      		</span>

		      		<div style="height: 25px">
		      			<span us-spinner="{radius:10, width:4, length: 8, color: '#2c97d1'}" spinner-on="showSpinnerEditPaciente"></span>
		      		</div>
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalErroLoadPacienteEdit">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Erro</h4>
	      		</div>

		      	<div class="modal-body">
		      		<span>
		      			Erro ao carregar o paciente. Verifique sua conexão com a internet e tente novamente.
		      		</span>
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
	  	</div>
	</div>
	<!-- ----- Fim Edição de Pacientes ------- -->




	<!-- ----- Início View de Pacientes ------- -->
	<div ng-if="showViewPacientes" class="peb-containers atendimentos-page">
		<div class="content-records">
			<div>
				<div class="container-fluid p-l-s p-r-s">
					<h2 class="title-lg">Resumo</h2>

					<div class="container-patient p-s bd bd-gray bg-white" ng-if="!showSpinnerGetAtendimento">
						<div class="ib patient-data">
							<div class="data row">
								<p class="name c-ic-dark-blue semi-bold">
									<span>[[ viewPaciente.nome ]]</span>
								</p>
								<p class="col-sm-8">
									<span>Idade: </span>
									<strong>[[ calcIdade(viewPaciente.data_nasc) ]]</strong>
								</p>
								<p class="col-sm-8">
									<span>Atendimentos: </span>
									<strong>[[ qtdAtendimentos ]]</strong>
								</p>
							</div>
						</div>
						<div class="pull-right patient-detail-modal" ng-click="setPacienteEdit(viewPaciente)" ng-if="showIniciarAtendimento">
							<span class="btn btn-blue text-medium semi-bold">VISUALIZAR CADASTRO</span>
						</div>
					</div>
				</div>

				<div ng-show="viewResumo" ng-if="!showSpinnerGetAtendimento">
					<div>
						<div>
							<div class="container-fluid p-t-s p-b-s p-l-s p-r-s" ng-if="qtdAtendimentos == 0">
								<div class="alert alert-warning">
									<p>
										<span>Não há atendimentos no momento. <br>Para iniciar um atendimento, clique no botão</span>
										<strong>Iniciar Atendimento</strong>
									</p>
								</div>
							</div>

							<ul class="p-s list-records reset-list" ng-if="qtdAtendimentos > 0">
								<!-- <li class="item-records p-b-l clearfix">
									<div class="date-record pull-left">
										<div class="date">
											<span class="day">13</span>
											<span class="monthy desktop">JUL</span>
											<span class="monthy mobile">07</span>
											<span class="year">2017</span>
										</div>
									</div>

									<div class="content-record pull-left">
										<div class="content-record-inner bg-white bd bd-gray">
											<div class="header-record reset-text p-s clearfix">
												<p class="pull-left bold physician-name">
													<span>Por: </span>
													<span>Dr. Teste</span>
													<span> </span>
													<span>
														<span class="f-lock event-share-icon"></span>
													</span>
												</p>

												<p class="pull-right normal c-ic-blue event-duration">
													<span class="glyphicon glyphicon-time"></span>
													<span> </span>
													<span>16:50</span>
													<span> </span>
													<span>(9 minutos)</span>
												</p>
											</div>

											<div class="record-descritption">
												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
													<span class="c-ic-blue semi-bold pull-left">Exame físico</span>
													<span ></span>
												</h2>

												<div class="item-text p-s">
													<p>
														<span>Altura:</span>
														<span> </span>
														<strong>
															<span>44</span>
															<span> </span>
															<span>m</span>
														</strong>
													</p>

													<p>
														<span>Peso:</span>
														<span> </span>
														<strong>
															<span>44</span>
															<span> </span>
															<span>kg</span>
														</strong>
													</p>
												</div>
											</div>
										</div>
									</div>

									<span class="item-records-line"></span>
								</li> -->

								<table class="table table-default table-list table-list-patients table-atendimentos">
								    <thead>
								      	<tr>
								      		<th colspan="2" class="input-refresh-atends">
								      			<input type="text" ng-model="atendOffset" numbers-only>
								      			<span class="glyphicon glyphicon-search pointer" ng-click="refreshTableAtend()"></span>
								      		</th>

								      		<th ng-repeat="num in atendimentosNums">
									            [[ num ]]
									        </th>
								      	</tr>
								    </thead>
								    
								    <tbody>
								    	<th rowspan='[[countShowAtendKey(atendimentoKeys, "atendimento") + 1]]' ng-if="countShowAtendKey(atendimentoKeys, 'atendimento') > 0">
								    		Atendimento
								    	</th>

									    <tr ng-repeat="key in atendimentoKeys | filter : showAtendKey('atendimento')">
									      	<th>[[ key[1] ]]</th>
									      	<td ng-repeat="obj in atendimentos">
									        	[[ obj.atendimento[ key[0] ] ]]
									      	</td>
									    </tr>

									    <th rowspan='[[countShowAtendKey(medidasKeys, "medidas") + 1]]' ng-if="countShowAtendKey(medidasKeys, 'medidas') > 0">
								    		Medidas
								    	</th>

									    <tr ng-repeat="key in medidasKeys | filter : showAtendKey('medidas')">
									      	<th>[[ key[1] ]]</th>
									      	<td ng-repeat="obj in atendimentos">
									        	[[ obj.medidas[ key[0] ] ]]
									      	</td>
									    </tr>

									    <th rowspan='[[countShowAtendKey(planoFrontalKeys, "plano_frontal") + 1]]' ng-if="countShowAtendKey(planoFrontalKeys, 'plano_frontal') > 0">
								    		Plano Frontal
								    	</th>

									    <tr ng-repeat="key in planoFrontalKeys | filter : showAtendKey('plano_frontal')">
									      	<th>[[ key[1] ]]</th>
									      	<td ng-repeat="obj in atendimentos">
									        	[[ obj.plano_frontal[ key[0] ] ]]
									      	</td>
									    </tr>

									    <th rowspan='[[countShowAtendKey(planoSagitalKeys, "plano_sagital") + 1]]' ng-if="countShowAtendKey(planoSagitalKeys, 'plano_sagital') > 0">
								    		Plano Sagital
								    	</th>

									    <tr ng-repeat="key in planoSagitalKeys | filter : showAtendKey('plano_sagital')">
									      	<th>[[ key[1] ]]</th>
									      	<td ng-repeat="obj in atendimentos">
									        	[[ obj.plano_sagital[ key[0] ] ]]
									      	</td>
									    </tr>

									    <th rowspan='[[countShowAtendKey(planoHorizontalKeys, "plano_horizontal") + 1]]' ng-if="countShowAtendKey(planoHorizontalKeys, 'plano_horizontal') > 0">
								    		Plano Horizontal
								    	</th>

									    <tr ng-repeat="key in planoHorizontalKeys | filter : showAtendKey('plano_horizontal')">
									      	<th>[[ key[1] ]]</th>
									      	<td ng-repeat="obj in atendimentos">
									        	[[ obj.plano_horizontal[ key[0] ] ]]
									      	</td>
									    </tr>

									    <th rowspan='[[countShowAtendKey(mobilidadeArticularKeys, "mobilidade_articular") + 1]]' ng-if="countShowAtendKey(mobilidadeArticularKeys, 'mobilidade_articular') > 0">
								    		Mobilidade Articular
								    	</th>

									    <tr ng-repeat="key in mobilidadeArticularKeys | filter : showAtendKey('mobilidade_articular')">
									      	<th>[[ key[1] ]]</th>
									      	<td ng-repeat="obj in atendimentos">
									        	[[ obj.mobilidade_articular[ key[0] ] ]]
									      	</td>
									    </tr>

									    <th rowspan='[[countShowAtendKey(diagProgKeys, "diag_prog") + 1]]' ng-if="countShowAtendKey(diagProgKeys, 'diag_prog') > 0">
								    		Diagnóstico Prognóstico
								    	</th>

									    <tr ng-repeat="key in diagProgKeys | filter : showAtendKey('diag_prog')">
									      	<th>[[ key[1] ]]</th>
									      	<td ng-repeat="obj in atendimentos">
									        	[[ obj.diag_prog[ key[0] ] ]]
									      	</td>
									    </tr>

									    <th rowspan='[[countShowAtendKey(vertebraKeys, "vertebra") + 1]]'  ng-if="countShowAtendKey(vertebraKeys, 'vertebra') > 0">
								    		Vértebra
								    	</th>

									    <tr ng-repeat="key in vertebraKeys | filter : showAtendKey('vertebra')">
									      	<th>[[ key[1] ]]</th>
									      	<td ng-repeat="obj in atendimentos">
									        	[[ obj.vertebra[ key[0] ] ]]
									      	</td>
									    </tr>

									    <th rowspan='[[countShowAtendKey(localEscolioseKeys, "local_escoliose") + 1]]'  ng-if="countShowAtendKey(localEscolioseKeys, 'local_escoliose') > 0">
								    		Local Escoliose
								    	</th>

									    <tr ng-repeat="key in localEscolioseKeys | filter : showAtendKey('local_escoliose')">
									      	<th>[[ key[1] ]]</th>
									      	<td ng-repeat="obj in atendimentos">
									        	[[ obj.local_escoliose[ key[0] ] ]]
									      	</td>
									    </tr>

									    <th rowspan='[[countShowAtendKey(curvaKeys, "curva") + 1]]'  ng-if="countShowAtendKey(curvaKeys, 'curva') > 0">
								    		Curva
								    	</th>

									    <tr ng-repeat="key in curvaKeys | filter : showAtendKey('curva')">
									      	<th>[[ key[1] ]]</th>
									      	<td ng-repeat="obj in atendimentos">
									        	[[ obj.curva[ key[0] ] ]]
									      	</td>
									    </tr>
								    </tbody>
								</table>
							</ul>
						</div>
					</div>
				</div>

				<div ng-show="viewAtendimento" ng-if="showFinalizarAtendimento && !showSpinnerGetAtendimento">
					<div>
						<div class="p-t-s p-l-s p-r-s">
							<div>
								<div class="bd-radius bg-gray p-l m-b-m">
									<div class="content-records-exam box-shadow reset-text">
										<h2 class="item-title p-s bd-b bd-gray clearfix">
											<span class="c-ic-blue semi-bold pull-left">Atendimento</span>
											<span></span>
										</h2>

										<div>
											<ul class="reset-list p-s">
												<li>
													<div class="form-group form-group-sm size-710">
														<label>Idade cronológica:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="atendimento.idade_cronologica" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Idade óssea:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="atendimento.idade_ossea" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Menarca:</label>

														<div class="input-group">
															<input class="form-control" type="text" ng-model="atendimento.menarca" options="dpAtendimentoOptions" datetimepicker readonly>

															<span class="input-group-addon pointer">
														        <span class="glyphicon glyphicon-calendar"></span>
														    </span>
														</div>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Número do atendimento:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="atendimento.num_atendimento" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Data de atendimento:</label>
														
														<div class="input-group">
															<input class="form-control" type="text" ng-model="atendimento.data_atendimento" options="dpAtendimentoOptions" datetimepicker readonly>

															<span class="input-group-addon pointer">
														        <span class="glyphicon glyphicon-calendar"></span>
														    </span>
														</div>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Altura:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="atendimento.altura" floating-number-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Altura sentada:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="atendimento.altura_sentada" floating-number-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Peso:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="atendimento.peso" floating-number-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Risser:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="atendimento.risser" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Data do raio X:</label>

														<div class="input-group">														
															<input class="form-control" type="text" ng-model="atendimento.data_raio_x" options="dpAtendimentoOptions" datetimepicker readonly>

															<span class="input-group-addon pointer">
														        <span class="glyphicon glyphicon-calendar"></span>
														    </span>
														</div>
													</div>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div ng-show="viewMedidas" ng-if="showFinalizarAtendimento && !showSpinnerGetAtendimento">
					<div>
						<div class="p-t-s p-l-s p-r-s">
							<div>
								<div class="bd-radius bg-gray p-l m-b-m">
									<div class="content-records-exam box-shadow reset-text">
										<h2 class="item-title p-s bd-b bd-gray clearfix">
											<span class="c-ic-blue semi-bold pull-left">Medidas</span>
											<span></span>
										</h2>

										<div>
											<ul class="reset-list p-s">
												<li>
													<div class="form-group form-group-sm size-710">
														<label>Assimentria ombro:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.assimetria_ombro" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Assimetria escápulas:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.assimetria_escapulas" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Hemi-Tórax:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.hemi_torax" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Cintura:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.cintura" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Teste Fukuda deslocamento:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.teste_fukuda_deslocamento" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Teste Fukuda rotação:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.teste_fukuda_rotacao" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Teste Fukuda desvio:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.teste_fukuda_desvio" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Habilidade ocular direito:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.habilidade_ocular_direito">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Habilidade ocular esquerdo:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.habilidade_ocular_esquerdo">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Romberg mono direito:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.romberg_mono_direito" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Romberg mono esquerdo:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.romberg_mono_esquerdo" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Romberg sensibilizado direito:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.romberg_sensibilizado_direito" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Romberg sensibilizado esquerdo:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.romberg_sensibilizado_esquerdo" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Balanço direito:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.balanco_direito">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Balanço esquerdo:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.balanco_esquerdo">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Retração posterior:</label>
														<input class="form-control" maxlength="254" type="text"  ng-model="medidas.retracao_posterior" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Teste Thomas direito:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.teste_thomas_direito" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Teste Thomas esquerdo:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.teste_thomas_esquerdo" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Retração peitoral direito:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.retracao_peitoral_direito" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Retração peitoral esquerdo:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.retracao_peitoral_esquerdo" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Força muscular ABS:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.forca_muscular_abs" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Força ext. tronco:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.forca_ext_tronco" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Resistência extensores tronco:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="medidas.resistencia_extensores_tronco" numbers-only>
													</div>
												</li>
											</ul>
										</div>
									</div>

									<div class="content-records-exam box-shadow reset-text">
										<h2 class="item-title p-s bd-b bd-gray clearfix">
											<span class="c-ic-blue semi-bold pull-left">Plano Frontal</span>
											<span></span>
										</h2>

										<div>
											<ul class="reset-list p-s">
												<li>
													<div class="form-group form-group-sm size-710">
														<label>Valor:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="plano_frontal.valor" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Calço:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="plano_frontal.calco">
													</div>
												</li>
											</ul>
										</div>
									</div>

									<div class="content-records-exam box-shadow reset-text">
										<h2 class="item-title p-s bd-b bd-gray clearfix">
											<span class="c-ic-blue semi-bold pull-left">Plano Horizontal</span>
											<span></span>
										</h2>

										<div>
											<ul class="reset-list p-s">
												<li>
													<div class="form-group form-group-sm size-710">
														<label>Valor:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="plano_horizontal.valor" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Tipo:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="plano_horizontal.tipo" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Calço:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="plano_horizontal.calco">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Vértebra:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="plano_horizontal.vertebra">
													</div>
												</li>
											</ul>
										</div>
									</div>

									<div class="content-records-exam box-shadow reset-text">
										<h2 class="item-title p-s bd-b bd-gray clearfix">
											<span class="c-ic-blue semi-bold pull-left">Plano Sagital</span>
											<span></span>
										</h2>

										<div>
											<ul class="reset-list p-s">
												<li>
													<div class="form-group form-group-sm size-710">
														<label>Valor:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="plano_sagital.valor" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Diferença:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="plano_sagital.diferenca" numbers-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Localização:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="plano_sagital.localizacao">
													</div>
												</li>
											</ul>
										</div>
									</div>

									<div class="content-records-exam box-shadow reset-text">
										<h2 class="item-title p-s bd-b bd-gray clearfix">
											<span class="c-ic-blue semi-bold pull-left">Mobilidade Articular</span>
											<span></span>
										</h2>

										<div>
											<ul class="reset-list p-s">
												<li>
													<div class="form-group form-group-sm size-710">
														<label>Valor:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="mobilidade_articular.valor" floating-number-only>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Inclinação:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="mobilidade_articular.inclinacao">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Lado:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="mobilidade_articular.lado">
													</div>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div ng-show="viewDiagProg" ng-if="showFinalizarAtendimento && !showSpinnerGetAtendimento">
					<div>
						<div class="p-t-s p-l-s p-r-s">
							<div>
								<div class="bd-radius bg-gray p-l m-b-m">
									<div class="content-records-exam box-shadow reset-text">
										<h2 class="item-title p-s bd-b bd-gray clearfix">
											<span class="c-ic-blue semi-bold pull-left">Diagnóstico Prognóstico</span>
											<span></span>
										</h2>

										<div>
											<ul class="reset-list p-s">
												<li>
													<div class="form-group form-group-sm size-710">
														<label>Diagnóstico clínico:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="diag_prog.diagnostico_clinico">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Tipo escoliose:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="diag_prog.tipo_escoliose">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Cifose:</label>

														<select class="select-picker form-control" ng-model="diag_prog.cifose">
															<option value="" selected="selected"></option>
															<option value="Sim">Sim</option>
															<option value="Não">Não</option>
														</select>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Lordose:</label>

														<select class="select-picker form-control" ng-model="diag_prog.lordose">
															<option value="" selected="selected"></option>
															<option value="Sim">Sim</option>
															<option value="Não">Não</option>
														</select>
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Prescrição médica:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="diag_prog.prescricao_medica">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Prescrição fisioterapêutica:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="diag_prog.prescricao_fisioterapeutica">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Colete:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="diag_prog.colete">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Colete HS:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="diag_prog.colete_hs">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Etiologia:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="diag_prog.etiologia">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Idade do aparecimento:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="diag_prog.idade_aparecimento">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Topografia:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="diag_prog.topografia">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Calço:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="diag_prog.calco">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>HPP:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="diag_prog.hpp">
													</div>
												</li>
											</ul>
										</div>
									</div>

									<div class="content-records-exam box-shadow reset-text">
										<h2 class="item-title p-s bd-b bd-gray clearfix">
											<span class="c-ic-blue semi-bold pull-left">Curva</span>
											<span></span>
										</h2>

										<div>
											<ul class="reset-list p-s">
												<li>
													<div class="form-group form-group-sm size-710">
														<label>Ordenação:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="curva.ordenacao">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Tipo:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="curva.tipo">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Ângulo de COBB:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="curva.angulo_cobb">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Ângulo Ferguson:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="curva.angulo_ferguson">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Grau de rotação:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="curva.grau_rotacao">
													</div>
												</li>
											</ul>
										</div>
									</div>

									<div class="content-records-exam box-shadow reset-text">
										<h2 class="item-title p-s bd-b bd-gray clearfix">
											<span class="c-ic-blue semi-bold pull-left">Local Escoliose</span>
											<span></span>
										</h2>

										<div>
											<ul class="reset-list p-s">
												<li>
													<div class="form-group form-group-sm size-710">
														<label>Local:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="local_escoliose.local">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Lado:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="local_escoliose.lado">
													</div>
												</li>
											</ul>
										</div>
									</div>

									<div class="content-records-exam box-shadow reset-text">
										<h2 class="item-title p-s bd-b bd-gray clearfix">
											<span class="c-ic-blue semi-bold pull-left">Vértebra</span>
											<span></span>
										</h2>

										<div>
											<ul class="reset-list p-s">
												<li>
													<div class="form-group form-group-sm size-710">
														<label>Tipo</label>
														<input class="form-control" maxlength="254" type="text" ng-model="vertebra.tipo">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Local:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="vertebra.local">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Altura:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="vertebra.altura">
													</div>
												</li>

												<li>
													<div class="form-group form-group-sm size-710">
														<label>Nome da vértebra:</label>
														<input class="form-control" maxlength="254" type="text" ng-model="vertebra.vertebra_nome">
													</div>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="spinnerAtendimento">
					<span us-spinner="{radius:30, width:8, length: 16, color: '#2c97d1'}" spinner-on="showSpinnerGetAtendimento"></span>
				</div>
			</div>
		</div>
	</div>

	<!-- ---- Modal de atendimento -->

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalFinalizarAtendimento">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Finalizar atendimento</h4>
	      		</div>

		      	<div class="modal-body">
			        Ao finalizar um atendimento, você não poderá alterá-lo novamente. Deseja prosseguir?
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			        <button type="button" class="btn btn-green btn-loading" data-dismiss="modal" ng-click="addAtendimento()">FINALIZAR</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalErroAddAtendimento">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Adicionando...</h4>
	      		</div>

		      	<div class="modal-body">
		      		<span ng-if="!showSpinnerAddAtendimento">
		      			Erro ao adicionar atendimento. Verifique sua conexão com a internet e tente novamente.
		      		</span>

		      		<div style="height: 25px">
		      			<span us-spinner="{radius:10, width:4, length: 8, color: '#2c97d1'}" spinner-on="showSpinnerAddAtendimento"></span>
		      		</div>
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalCancelarAtendimento">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Cancelar atendimento</h4>
	      		</div>

		      	<div class="modal-body">
			        Ao cancelar um atendimento, você perderá todos os dados não salvos. Deseja prosseguir?
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			        <button type="button" class="btn btn-green btn-loading" data-dismiss="modal" ng-click="cancelarAtendimento()">SIM</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalErroCarregarAtendimentos">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Carregando...</h4>
	      		</div>

		      	<div class="modal-body">
		      		Erro ao carregar atendimentos. Verifique sua conexão com a internet e tente novamente.
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalErroTabelaAtendimentos">
	  	<div class="modal-dialog modal-sm" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Carregando...</h4>
	      		</div>

		    	<div class="modal-body">
		      		<span ng-if="!showSpinnerTabelaAtendimentos">
		      			Erro ao atualizar tabela de atendimentos. Verifique sua conexão com a internet e tente novamente.
		      		</span>

		      		<div style="height: 25px">
		      			<span us-spinner="{radius:10, width:4, length: 8, color: '#2c97d1'}" spinner-on="showSpinnerTabelaAtendimentos"></span>
		      		</div>
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<!-- ----- Fim View de Pacientes ------- -->
</div>

@endsection
