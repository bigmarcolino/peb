@extends('usuario.layout.auth')

@section('content')

@if (Auth::user()->funcao != "Analista")
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
@endif

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

		      		<div style="min-height: 25px">
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
	                        <input id="name" type="text" class="form-control" name="name" placeholder="Nome:" ng-model="usuarioEdit.name" ng-change="checkNomeEditarUsuario()" maxlength="254" autofocus>

	                        <span class="help-block" ng-if="nomeVazioEditarUsuario">
                                <strong>O campo nome é obrigatório</strong>
                            </span>
	                    </div>

	                    <div class="form-group" ng-class="{'has-error': emailVazioEditarUsuario || emailExisteEditarUsuario}">
	                        <input id="email" type="email" class="form-control" name="email" placeholder="Email:" ng-model="usuarioEdit.email" ng-change="checkEmailEditarUsuario(); checkEmailExistenciaEditarUsuario()" maxlength="254">

	                        <span class="help-block" ng-if="emailExisteEditarUsuario">
                                <strong>O email já existe</strong>
                            </span>

                            <span class="help-block" ng-if="emailVazioEditarUsuario">
                                <strong>O campo email é obrigatório</strong>
                            </span>
	                    </div>

	                    <div class="form-group">
	                        <select id="sexo" class="form-control" name="sexo" ng-model="usuarioEdit.sexo" maxlength="254">
	                            <option ng-selected="usuarioEdit.sexo == 'Masculino'">Masculino</option>
	                            <option ng-selected="usuarioEdit.sexo == 'Feminino'">Feminino</option>
	                        </select>
	                    </div>

	                    <div class="form-group">
	                    	<div class="input-group">
		                    	<input class="form-control" type="text" name="data_nasc" placeholder="Data de Nascimento:" ng-model="usuarioEdit.data_nasc" options="dpEditarUsuarioOptions" datetimepicker readonly maxlength="254">
							    <span class="input-group-addon" id="data_nasc">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </span>
							</div>
	                    </div>

	                    <div class="form-group" ng-if="cpfLogged() != usuarioEdit.cpf">
	                        <select id="funcao" class="form-control" name="funcao" ng-model="usuarioEdit.funcao" maxlength="254">
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

		      		<div style="min-height: 25px">
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

		      		<div style="min-height: 25px">
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
                                <input type="text" class="form-control" ng-model="novoPaciente.nome" ng-change="checkNomePaciente('add')" maxlength="254">

                                <span class="help-block" ng-if="nomeVazioPaciente">
	                                <strong>O campo nome é obrigatório</strong>
	                            </span>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">CPF*</label>
                            <div class="col-sm-2" ng-class="{'has-error': cpfVazioPaciente || cpfExistePaciente}">
                                <input type="text" class="form-control" ng-model="novoPaciente.cpf" numbers-only ng-change="checkCpfPaciente()" ng-blur="checkCpfExistenciaPaciente()" maxlength="254">

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
                            <div class="col-sm-1">
                                <input type="text" class="form-control" ng-model="novoPaciente.identidade" numbers-only maxlength="254">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-sm form-birthday">
                    	<div>
	                        <label class="col-sm-2 control-label">Data de nascimento*</label>
	                        <div class="col-sm-3" ng-class="{'has-error': dataVazioPaciente}">
	                        	<div class="input-group">
		                            <input class="form-control" type="text" ng-model="novoPaciente.data_nasc" options="dpNovoPacienteOptions" datetimepicker readonly ng-change="checkDataPaciente('add'); checkMaioridadePaciente('add')">

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
	                            <input class="form-control" type="text" ng-model="novoPaciente.email" maxlength="254">
	                        </div>
	                    </div>
                    </div>
                   
                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Médico</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" ng-model="novoPaciente.medico" maxlength="254">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Indicação</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" ng-model="novoPaciente.indicacao" maxlength="254">
                            </div>
                        </div>
                    </div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper" ng-if="pacienteMenorIdade">Responsável</h3>

                    <div class="form-group form-group-sm" ng-if="pacienteMenorIdade">
                        <div>
                            <label class="col-sm-2 control-label">Nome*</label>
                            <div class="col-sm-3" ng-class="{'has-error': nomeVazioResponsavel}">
                                <input type="text" class="form-control" ng-model="novoResponsavel.nome" ng-change="checkNomeResponsavel('add')" maxlength="254">

                                <span class="help-block" ng-if="nomeVazioResponsavel">
	                                <strong>O campo nome é obrigatório</strong>
	                            </span>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">CPF*</label>
                            <div class="col-sm-2" ng-class="{'has-error': cpfVazioResponsavel || cpfExisteResponsavel}">
                                <input type="text" class="form-control" ng-model="novoResponsavel.cpf" numbers-only ng-change="checkCpfResponsavel()" ng-blur="checkCpfExistenciaResponsavel()" maxlength="254">

                                <span class="help-block" ng-if="cpfExisteResponsavel">
	                                <strong>O CPF já existe</strong>
	                            </span>

                                <span class="help-block" ng-if="cpfVazioResponsavel">
	                                <strong>O campo CPF é obrigatório</strong>
	                            </span>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Identidade</label>
                            <div class="col-sm-1">
                                <input type="text" class="form-control" ng-model="novoResponsavel.identidade" numbers-only maxlength="254">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-sm" ng-if="pacienteMenorIdade">
                        <div>
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" ng-model="novoResponsavel.email" maxlength="254">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Ocupação</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" ng-model="novoResponsavel.ocupacao" maxlength="254">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Telefone</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" ng-model="novoResponsavel.telefone" numbers-only maxlength="254">
                            </div>
                        </div>
                    </div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper">Telefones</h3>

                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Celular</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" ng-model="novoPaciente.celular" numbers-only maxlength="254">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Casa</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" ng-model="novoPaciente.tel_res" numbers-only maxlength="254">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Trabalho</label>
                            <div class="col-sm-2">
                                <input class="form-control" id="id_office_phone" type="text" ng-model="novoPaciente.tel_trab" numbers-only maxlength="254">
                            </div>
                        </div>
                    </div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper">Endereço</h3>

                    <div>
                        <div class="form-group form-group-sm">
                        	<div>
                                <label class="col-sm-2 control-label">Endereço</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="text" ng-model="novoPaciente.end_res" maxlength="254">
                                </div>
                            </div>

                            <div>
                                <label class="col-sm-1 control-label">CEP</label>
                                <div class="col-sm-2">
                                    <input class="form-control" type="text" ng-model="novoPaciente.cep" numbers-only maxlength="254">
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-sm">
                            <div>
                                <label class="col-sm-2 control-label">Cidade</label>
                                <div class="col-sm-3">
                                    <input class="form-control" type="text" ng-model="novoPaciente.cidade" maxlength="254">
                                </div>
                            </div>
                            <div>
                                <label class="col-sm-1 control-label">Estado</label>

                                <div class="col-sm-2">
	                              	<select class="select-picker form-control" ng-model="novoPaciente.estado" maxlength="254">
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
				        <button type="button" class="btn btn-green upper pull-right btn-loading" id="form-submit-button" ng-click="addPaciente()" ng-disabled="ataVazioPaciente || nomeVazioPaciente || cpfVazioPaciente || cpfExistePaciente || (pacienteMenorIdade && nomeVazioResponsavel) || (pacienteMenorIdade && cpfVazioResponsavel) || (pacienteMenorIdade && cpfExisteResponsavel)">
				        	<span class="btn-loading-text">Salvar</span>			            	
			            </button>

			            <button type="button" class="btn btn-default upper m-r-es pull-left btn-loading" ng-click="addPaciente(true)" ng-disabled="dataVazioPaciente || nomeVazioPaciente || cpfVazioPaciente || cpfExistePaciente || (pacienteMenorIdade && nomeVazioResponsavel) || (pacienteMenorIdade && cpfVazioResponsavel) || (pacienteMenorIdade && cpfExisteResponsavel)">
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

		      		<div style="min-height: 25px">
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
	                                <input type="text" class="form-control" ng-model="pacienteEdit.nome" ng-change="checkNomePaciente('edit')" maxlength="254">

	                                <span class="help-block" ng-if="nomeVazioPaciente">
		                                <strong>O campo nome é obrigatório</strong>
		                            </span>
	                            </div>
	                        @else
	                        	<div class="col-sm-3">
	                                <input type="text" class="form-control" ng-model="pacienteEdit.nome" maxlength="254">
	                            </div>
	                        @endif
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Identidade</label>
                            <div class="col-sm-1">
                                <input type="text" class="form-control" ng-model="pacienteEdit.identidade" numbers-only maxlength="254">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-sm form-birthday">
                    	<div>
	                        <label class="col-sm-2 control-label">Data de nascimento*</label>
	                        <div class="col-sm-3" ng-class="{'has-error': dataVazioPaciente}">
	                        	<div class="input-group">
		                            <input class="form-control" type="text" ng-model="pacienteEdit.data_nasc" options="dpNovoPacienteOptions" datetimepicker readonly ng-change="checkDataPaciente('edit')" maxlength="254">

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
	                            <input class="form-control" type="text" ng-model="pacienteEdit.email" maxlength="254">
	                        </div>
	                    </div>
                    </div>
                   
                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Médico</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" ng-model="pacienteEdit.medico" maxlength="254">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Indicação</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" ng-model="pacienteEdit.indicacao" maxlength="254">
                            </div>
                        </div>
                    </div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper" ng-if="pacienteMenorIdade">Responsável</h3>

                    <div class="form-group form-group-sm" ng-if="pacienteMenorIdade">
                        <div>
                            <label class="col-sm-2 control-label">Nome*</label>
                            <div class="col-sm-3" ng-class="{'has-error': nomeVazioResponsavel}">
                                <input type="text" class="form-control" ng-model="responsavelEdit.nome" ng-change="checkNomeResponsavel('edit')" maxlength="254">

                                <span class="help-block" ng-if="nomeVazioResponsavel">
	                                <strong>O campo nome é obrigatório</strong>
	                            </span>
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Identidade</label>
                            <div class="col-sm-1">
                                <input type="text" class="form-control" ng-model="responsavelEdit.identidade" numbers-only maxlength="254">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-sm" ng-if="pacienteMenorIdade">
                        <div>
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" ng-model="responsavelEdit.email" maxlength="254">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Ocupação</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" ng-model="responsavelEdit.ocupacao" maxlength="254">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Telefone</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" ng-model="responsavelEdit.telefone" numbers-only maxlength="254">
                            </div>
                        </div>
                    </div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper">Telefones</h3>

                    <div class="form-group form-group-sm">
                        <div>
                            <label class="col-sm-2 control-label">Celular</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" ng-model="pacienteEdit.celular" numbers-only maxlength="254">
                            </div>
                        </div>

                        <div>
                            <label class="col-sm-1 control-label">Casa</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" ng-model="pacienteEdit.tel_res" numbers-only maxlength="254">
                            </div>
                        </div>
                        <div>
                            <label class="col-sm-1 control-label">Trabalho</label>
                            <div class="col-sm-2">
                                <input class="form-control" type="text" ng-model="pacienteEdit.tel_trab" numbers-only maxlength="254">
                            </div>
                        </div>
                    </div>

                    <h3 class="title-form text-medium semi-bold p-b-s p-t-el c-ic-blue upper">Endereço</h3>

                    <div data-zipcode="context">
                        <div class="form-group form-group-sm">
                        	<div>
                                <label class="col-sm-2 control-label">Endereço</label>
                                <div class="col-sm-4">
                                    <input class="form-control" type="text" ng-model="pacienteEdit.end_res" maxlength="254">
                                </div>
                            </div>

                            <div>
                                <label class="col-sm-1 control-label">CEP</label>
                                <div class="col-sm-2">
                                    <input class="form-control" type="text" ng-model="pacienteEdit.cep" numbers-only maxlength="254">
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-group-sm">
                            <div>
                                <label class="col-sm-2 control-label">Cidade</label>
                                <div class="col-sm-3">
                                    <input class="form-control" type="text" ng-model="pacienteEdit.cidade" maxlength="254">
                                </div>
                            </div>
                            <div>
                                <label class="col-sm-1 control-label">Estado</label>

                                <div class="col-sm-2">
	                              	<select class="select-picker form-control" ng-model="pacienteEdit.estado" maxlength="254">
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
					        <button type="button" class="btn btn-green upper pull-right btn-loading" id="form-submit-button" ng-click="editarPaciente()" ng-disabled="nomeVazioPaciente || (pacienteMenorIdade && nomeVazioResponsavel)">
					        	<span class="btn-loading-text">Salvar</span>			            	
				            </button>

				            <button type="button" class="btn btn-default upper m-r-es pull-left btn-loading" ng-click="editarPaciente(true)" ng-disabled="nomeVazioPaciente || (pacienteMenorIdade && nomeVazioResponsavel)">
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

		      		<div style="min-height: 25px">
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
	@if (Auth::user()->funcao != "Analista")
	<div ng-if="showViewPacientes" class="peb-containers atendimentos-page">
	@else
	<div ng-if="showViewPacientes" class="peb-containers">
	@endif
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

						<div class="pull-right patient-detail-modal" ng-click="setDadosPaciente()" data-toggle="modal" data-target="#modalDadosPaciente">
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
								<div ng-class="{'p-s bg-white bd bd-gray bd-radius': tabAtendimento == -1}">
									<table class="table table-default table-list table-list-patients table-atendimentos">
										<col style="width: 8%" ng-if="tabAtendimento == -1">
										<col style="width: 8%" ng-if="tabAtendimento == -1">

									    <thead>
									      	<tr>
									      		<th colspan="2" class="input-refresh-atends no-border-atendimentos" ng-show="!showAtendimento">
									      			<input type="text" ng-model="atendOffset" numbers-only style="vertical-align: middle; border: 1px solid #ccc; width: 80px">
									      			
													<span class="btn btn-sm btn-default" ng-click="refreshTableAtend()" style="height: 22px; padding-top: 2px">
														<span class="glyphicon glyphicon-search pointer" style="margin: 0"></span>
													</span>
									      		</th>

									      		<th ng-repeat="num in atendimentosNums" ng-click="toggleViewAtendimento($index)" class="pointer" ng-class="{'tab-open-atendimentos': $index == tabAtendimento, 'no-border-atendimentos': $index != tabAtendimento}">
										            [[ num ]]
										        </th>
									      	</tr>
									    </thead>
									    
									    <tbody ng-show="!showAtendimento">
									    	<th rowspan='[[countShowAtendKey(atendimentoKeys, "atendimento") + 1]]' ng-if="countShowAtendKey(atendimentoKeys, 'atendimento') > 0" style="border-top: none">
									    		Atendimento
									    	</th>

										    <tr ng-repeat="key in atendimentoKeys | filter : showAtendKey('atendimento')">
										      	<th style="border-top: none">[[ key[1] ]]</th>
										      	<td ng-repeat="obj in atendimentos" style="border-top: none">
										        	[[ obj.atendimento[ key[0] ] ]]
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

										    <th rowspan='[[countShowAtendKey(planoHorizontalKeys, "plano_horizontal") + 1]]' ng-if="countShowAtendKey(planoHorizontalKeys, 'plano_horizontal') > 0">
									    		Plano Horizontal
									    	</th>

										    <tr ng-repeat="key in planoHorizontalKeys | filter : showAtendKey('plano_horizontal')">
										      	<th>[[ key[1] ]]</th>
										      	<td ng-repeat="obj in atendimentos">
										        	[[ obj.plano_horizontal[ key[0] ] ]]
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

										    <th rowspan='[[countShowAtendKey(medidasOneKeys, "medidas") + 1]]' ng-if="countShowAtendKey(medidasOneKeys, 'medidas') > 0">
									    		Medidas
									    	</th>

										    <tr ng-repeat="key in medidasOneKeys | filter : showAtendKey('medidas')">
										      	<th>[[ key[1] ]]</th>
										      	<td ng-repeat="obj in atendimentos">
										        	[[ obj.medidas[ key[0] ] ]]
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

										    <th rowspan='[[countShowAtendKey(medidasTwoKeys, "medidas") + 1]]' ng-if="countShowAtendKey(medidasTwoKeys, 'medidas') > 0">
									    		Medidas
									    	</th>

										    <tr ng-repeat="key in medidasTwoKeys | filter : showAtendKey('medidas')">
										      	<th>[[ key[1] ]]</th>
										      	<td ng-repeat="obj in atendimentos">
										        	[[ obj.medidas[ key[0] ] ]]
										      	</td>
										    </tr>
									    </tbody>
									</table>
								</div>

								<li class="item-records p-b-l clearfix" ng-show="showAtendimento">
									<div class="date-record pull-left">
										<div class="date date-atendimento">
											<span class="day">[[ dataHoraAtendimento.dia ]]</span>
											<span class="monthy desktop">[[ dataHoraAtendimento.mesExtenso | uppercase ]]</span>
											<span class="monthy mobile">[[ dataHoraAtendimento.mes ]]</span>
											<span class="year">[[ dataHoraAtendimento.ano ]]</span>
										</div>
									</div>

									<div class="content-record pull-left">
										<div class="content-record-inner bg-white bd bd-gray">
											<div class="header-record reset-text p-s clearfix">
												<p class="pull-left bold physician-name pointer">
													<span class="glyphicon glyphicon-camera"></span>
													<span> - 0</span>
												</p>

												<p class="pull-right normal c-ic-blue event-duration">
													<span class="glyphicon glyphicon-time"></span>
													<span> </span>
													<span>[[ dataHoraAtendimento.hora ]]</span>
												</p>
											</div>

											<div class="record-descritption">
												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Atendimento</span>
														<span></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Idade cronológica:</label>
													        	<p class="form-control-static">[[ atendimentoFull.atendimento.idade_cronologica ]]</p>
													        </div>

													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Idade óssea:</label>
																<p class="form-control-static">[[ atendimentoFull.atendimento.idade_ossea ]]</p>
													        </div>

													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Menarca:</label>
																<p class="form-control-static">[[ atendimentoFull.atendimento.menarca ]]</p>
													        </div>

													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Altura:</label>
																<p class="form-control-static">[[ atendimentoFull.atendimento.altura ]]</p>
													        </div>
													    </div>

													    <div class="form-group row">
													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Altura sentada:</label>
																<p class="form-control-static">[[ atendimentoFull.atendimento.altura_sentada ]]</p>
													        </div>

													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Peso:</label>
																<p class="form-control-static">[[ atendimentoFull.atendimento.peso ]]</p>
													        </div>

													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Risser:</label>
																<p class="form-control-static">[[ atendimentoFull.atendimento.risser ]]</p>
													        </div>

													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Data do raio X:</label>
																<p class="form-control-static">[[ atendimentoFull.atendimento.data_raio_x ]]</p>
													        </div>
													    </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Plano Frontal</span>
														<span></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Valor:</label>
																<p class="form-control-static">[[ atendimentoFull.plano_frontal.valor ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Calço:</label>
																<p class="form-control-static">[[ atendimentoFull.plano_frontal.calco ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Plano Horizontal</span>
														<span></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Valor:</label>
																<p class="form-control-static">[[ atendimentoFull.plano_horizontal.valor ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Tipo:</label>
																<p class="form-control-static">[[ atendimentoFull.plano_horizontal.tipo ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Calço:</label>
																<p class="form-control-static">[[ atendimentoFull.plano_horizontal.calco ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Vértebra:</label>
																<p class="form-control-static">[[ atendimentoFull.plano_horizontal.vertebra ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Plano Sagital</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Valor:</label>
																<p class="form-control-static">[[ atendimentoFull.plano_sagital.valor ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Diferença:</label>
																<p class="form-control-static">[[ atendimentoFull.plano_sagital.diferenca ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Localização:</label>
																<p class="form-control-static">[[ atendimentoFull.plano_sagital.localizacao ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Assimetria</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Ombro:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.assimetria_ombro ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Escápulas:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.assimetria_escapulas ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Hemi-Tórax</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Hemi-Tórax:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.hemi_torax ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Cintura</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Cintura:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.cintura ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Mobilidade Articular</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Valor:</label>
																<p class="form-control-static">[[ atendimentoFull.mobilidade_articular.valor ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Inclinação:</label>
																<p class="form-control-static">[[ atendimentoFull.mobilidade_articular.inclinacao ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Lado:</label>
																<p class="form-control-static">[[ atendimentoFull.mobilidade_articular.lado ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Teste Fukuda</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Deslocamento:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.teste_fukuda_deslocamento ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Rotação:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.teste_fukuda_rotacao ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Desvio:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.teste_fukuda_desvio ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Habilidade Ocular</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Direito:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.habilidade_ocular_direito ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Esquerdo:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.habilidade_ocular_esquerdo ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Romberg Mono</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Direito:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.romberg_mono_direito ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Esquerdo:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.romberg_mono_esquerdo ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Romberg Sensibilizado</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Direito:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.romberg_sensibilizado_direito ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Esquerdo:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.romberg_sensibilizado_esquerdo ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Balanço</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Direito:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.balanco_direito ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Esquerdo:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.balanco_esquerdo ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Retração Posterior</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Retração posterior:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.retracao_posterior ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Teste Thomas</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Direito:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.teste_thomas_direito ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Esquerdo:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.teste_thomas_esquerdo ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Retração Peitoral</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Direito:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.retracao_peitoral_direito ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Esquerdo:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.retracao_peitoral_esquerdo ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Força Muscular</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>ABS:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.forca_muscular_abs ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Extensores Tronco</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Força:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.forca_ext_tronco ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Resistência:</label>
																<p class="form-control-static">[[ atendimentoFull.medidas.resistencia_extensores_tronco ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Diagnóstico Prognóstico</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
													        <div class="padding-full-input">
													        	<label>Diagnóstico clínico:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.diagnostico_clinico ]]</p>
													        </div>
													    </div>

													    <div class="form-group row">
													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Tipo escoliose:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.tipo_escoliose ]]</p>
													        </div>

													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Cifose:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.cifose ]]</p>
													        </div>

													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Lordose:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.lordose ]]</p>
													        </div>
													    </div>

													    <div class="form-group row">
													        <div class="padding-full-input">
													        	<label>Prescrição médica:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.prescricao_medica ]]</p>
													        </div>
													    </div>

													    <div class="form-group row">
													        <div class="padding-full-input">
													        	<label>Prescrição fisioterapêutica:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.prescricao_fisioterapeutica ]]</p>
													        </div>
													    </div>

													    <div class="form-group row">
													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Colete:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.colete ]]</p>
													        </div>

													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Colete HS:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.colete_hs ]]</p>
													        </div>

													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Idade do aparecimento:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.idade_aparecimento ]]</p>
													        </div>

													        <div class="col-md-3 col-sm-3 col-xs-6">
													        	<label>Calço:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.calco ]]</p>
													        </div>
													    </div>

													    <div class="form-group row">
													        <div class="col-md-6 col-sm-6 col-xs-6">
													        	<label>Etiologia:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.etiologia ]]</p>
													        </div>

													        <div class="col-md-6 col-sm-6 col-xs-6">
													        	<label>Topografia:</label>
																<p class="form-control-static">[[ atendimentoFull.diag_prog.topografia ]]</p>
													        </div>
													    </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Curva</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-2 col-sm-3 col-xs-6">
												        		<label>Ordenação:</label>
																<p class="form-control-static">[[ atendimentoFull.curva.ordenacao ]]</p>
												        	</div>

												        	<div class="col-md-2 col-sm-3 col-xs-6">
												        		<label>Tipo:</label>
																<p class="form-control-static">[[ atendimentoFull.curva.tipo ]]</p>
												        	</div>

												        	<div class="col-md-2 col-sm-3 col-xs-6">
												        		<label>Ângulo de COBB:</label>
																<p class="form-control-static">[[ atendimentoFull.curva.angulo_cobb ]]</p>
												        	</div>

												        	<div class="col-md-2 col-sm-3 col-xs-6">
												        		<label>Ângulo Ferguson:</label>
																<p class="form-control-static">[[ atendimentoFull.curva.angulo_ferguson ]]</p>
												        	</div>

												        	<div class="col-md-2 col-sm-3 col-xs-6">
												        		<label>Grau de rotação:</label>
																<p class="form-control-static">[[ atendimentoFull.curva.grau_rotacao ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Local Escoliose</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Local:</label>
																<p class="form-control-static">[[ atendimentoFull.local_escoliose.local ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Lado:</label>
																<p class="form-control-static">[[ atendimentoFull.local_escoliose.lado ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">Vértebra</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="form-group row">
												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Tipo</label>
																<p class="form-control-static">[[ atendimentoFull.vertebra.tipo ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Local:</label>
																<p class="form-control-static">[[ atendimentoFull.vertebra.local ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Altura:</label>
																<p class="form-control-static">[[ atendimentoFull.vertebra.altura ]]</p>
												        	</div>

												        	<div class="col-md-3 col-sm-3 col-xs-6">
												        		<label>Nome:</label>
																<p class="form-control-static">[[ atendimentoFull.vertebra.vertebra_nome ]]</p>
												        	</div>
												        </div>
													</div>
												</div>

												<div class="item-description bd-t bd-gray">
													<h2 class="item-title p-s bd-b bd-gray clearfix">
														<span class="c-ic-blue semi-bold pull-left">HPP</span>
														<span ></span>
													</h2>

													<div class="item-text p-s">
														<div class="padding-full-input">
															<label>HPP:</label>
															<p class="form-control-static">[[ atendimentoFull.diag_prog.hpp ]]</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<span class="item-records-line"></span>
								</li>
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

										<div class="form-atendimento">
											<div class="form-group row">
										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Idade cronológica:</label>
													<input class="form-control" type="text" ng-model="atendimento.idade_cronologica" numbers-only maxlength="9">
										        </div>

										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Idade óssea:</label>
													<input class="form-control" type="text" ng-model="atendimento.idade_ossea" numbers-only maxlength="9">
										        </div>

										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Menarca:</label>

													<div class="input-group">
														<input class="form-control" type="text" ng-model="atendimento.menarca" options="dpAtendimentoOptions" datetimepicker readonly>

														<span class="input-group-addon pointer">
													        <span class="glyphicon glyphicon-calendar"></span>
													    </span>
													</div>
										        </div>

										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Altura:</label>
													<input class="form-control" type="text" ng-model="atendimento.altura" floating-number-only maxlength="6">
										        </div>
										    </div>

										    <div class="form-group row">
										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Altura sentada:</label>
													<input class="form-control" type="text" ng-model="atendimento.altura_sentada" floating-number-only maxlength="6">
										        </div>

										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Peso:</label>
													<input class="form-control" type="text" ng-model="atendimento.peso" floating-number-only maxlength="6">
										        </div>

										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Risser:</label>
													<input class="form-control" type="text" ng-model="atendimento.risser" numbers-only maxlength="9">
										        </div>

										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Data do raio X:</label>

													<div class="input-group">														
														<input class="form-control" type="text" ng-model="atendimento.data_raio_x" options="dpAtendimentoOptions" datetimepicker readonly>

														<div class="input-group-addon pointer">
													        <span class="glyphicon glyphicon-calendar"></span>
													    </div>
													</div>
										        </div>
										    </div>
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

										<uib-accordion>
										    <div uib-accordion-group class="panel-default" heading="Plano Frontal" is-open="true">
										    	<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Valor:</label>
														<input class="form-control" type="text" ng-model="plano_frontal.valor" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Calço:</label>
														<input class="form-control" type="text" ng-model="plano_frontal.calco" maxlength="254">
										        	</div>
										        </div>
										    </div>

										    <div uib-accordion-group class="panel-default" heading="Plano Horizontal">
										    	<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Valor:</label>
														<input class="form-control" type="text" ng-model="plano_horizontal.valor" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Tipo:</label>
														<input class="form-control" type="text" ng-model="plano_horizontal.tipo" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Calço:</label>
														<input class="form-control" type="text" ng-model="plano_horizontal.calco" maxlength="254">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Vértebra:</label>
														<input class="form-control" type="text" ng-model="plano_horizontal.vertebra" maxlength="254">
										        	</div>
										        </div>
										    </div>

										    <div uib-accordion-group class="panel-default" heading="Plano Sagital">
										    	<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Valor:</label>
														<input class="form-control" type="text" ng-model="plano_sagital.valor" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Diferença:</label>
														<input class="form-control" type="text" ng-model="plano_sagital.diferenca" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Localização:</label>
														<input class="form-control" type="text" ng-model="plano_sagital.localizacao" maxlength="254">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Assimetria">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Ombro:</label>
														<input class="form-control" type="text" ng-model="medidas.assimetria_ombro" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Escápulas:</label>
														<input class="form-control" type="text" ng-model="medidas.assimetria_escapulas" numbers-only maxlength="9">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Hemi-Tórax">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Hemi-Tórax:</label>
														<input class="form-control" type="text" ng-model="medidas.hemi_torax" numbers-only maxlength="9">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Cintura">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Cintura:</label>
														<input class="form-control" type="text" ng-model="medidas.cintura" numbers-only maxlength="9">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Mobilidade Articular">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Valor:</label>
														<input class="form-control" type="text" ng-model="mobilidade_articular.valor" floating-number-only maxlength="6">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Inclinação:</label>
														<input class="form-control" type="text" ng-model="mobilidade_articular.inclinacao" maxlength="254">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Lado:</label>
														<input class="form-control" type="text" ng-model="mobilidade_articular.lado" maxlength="254">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Teste Fukuda">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Deslocamento:</label>
														<input class="form-control" type="text" ng-model="medidas.teste_fukuda_deslocamento" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Rotação:</label>
														<input class="form-control" type="text" ng-model="medidas.teste_fukuda_rotacao" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Desvio:</label>
														<input class="form-control" type="text" ng-model="medidas.teste_fukuda_desvio" numbers-only maxlength="9">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Habilidade Ocular">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Direito:</label>
														<input class="form-control" type="text" ng-model="medidas.habilidade_ocular_direito" maxlength="254">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Esquerdo:</label>
														<input class="form-control" type="text" ng-model="medidas.habilidade_ocular_esquerdo" maxlength="254">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Romberg Mono">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Direito:</label>
														<input class="form-control" type="text" ng-model="medidas.romberg_mono_direito" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Esquerdo:</label>
														<input class="form-control" type="text" ng-model="medidas.romberg_mono_esquerdo" numbers-only maxlength="9">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Romberg Sensibilizado">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Direito:</label>
														<input class="form-control" type="text" ng-model="medidas.romberg_sensibilizado_direito" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Esquerdo:</label>
														<input class="form-control" type="text" ng-model="medidas.romberg_sensibilizado_esquerdo" numbers-only maxlength="9">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Balanço">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Direito:</label>
														<input class="form-control" type="text" ng-model="medidas.balanco_direito" maxlength="254">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Esquerdo:</label>
														<input class="form-control" type="text" ng-model="medidas.balanco_esquerdo" maxlength="254">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Retração Posterior">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Retração posterior:</label>
														<input class="form-control" type="text" ng-model="medidas.retracao_posterior" numbers-only maxlength="9">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Teste Thomas">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Direito:</label>
														<input class="form-control" type="text" ng-model="medidas.teste_thomas_direito" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Esquerdo:</label>
														<input class="form-control" type="text" ng-model="medidas.teste_thomas_esquerdo" numbers-only maxlength="9">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Retração Peitoral">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Direito:</label>
														<input class="form-control" type="text" ng-model="medidas.retracao_peitoral_direito" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Esquerdo:</label>
														<input class="form-control" type="text" ng-model="medidas.retracao_peitoral_esquerdo" numbers-only maxlength="9">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Força Muscular">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>ABS:</label>
														<input class="form-control" type="text" ng-model="medidas.forca_muscular_abs" numbers-only maxlength="9">
										        	</div>
										        </div>
											</div>

											<div uib-accordion-group class="panel-default" heading="Extensores Tronco">
												<div class="form-group row forms-accordion">
										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Força:</label>
														<input class="form-control" type="text" ng-model="medidas.forca_ext_tronco" numbers-only maxlength="9">
										        	</div>

										        	<div class="col-md-3 col-sm-3 col-xs-6">
										        		<label>Resistência:</label>
														<input class="form-control" type="text" ng-model="medidas.resistencia_extensores_tronco" numbers-only maxlength="9">
										        	</div>
										        </div>
											</div>
										</uib-accordion>
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

										<div class="form-atendimento">
											<div class="form-group row">
										        <div class="padding-full-input">
										        	<label>Diagnóstico clínico:</label>
													<input class="form-control" type="text" ng-model="diag_prog.diagnostico_clinico" maxlength="254">
										        </div>
										    </div>

										    <div class="form-group row">
										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Tipo escoliose:</label>
													<input class="form-control" type="text" ng-model="diag_prog.tipo_escoliose" maxlength="254">
										        </div>

										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Cifose:</label>

													<select class="select-picker form-control" ng-model="diag_prog.cifose" maxlength="254">
														<option value="" selected="selected"></option>
														<option value="1">Sim</option>
														<option value="0">Não</option>
													</select>
										        </div>

										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Lordose:</label>

													<select class="select-picker form-control" ng-model="diag_prog.lordose" maxlength="254">
														<option value="" selected="selected"></option>
														<option value="1">Sim</option>
														<option value="0">Não</option>
													</select>
										        </div>
										    </div>

										    <div class="form-group row">
										        <div class="padding-full-input">
										        	<label>Prescrição médica:</label>
													<input class="form-control" type="text" ng-model="diag_prog.prescricao_medica" maxlength="254">
										        </div>
										    </div>

										    <div class="form-group row">
										        <div class="padding-full-input">
										        	<label>Prescrição fisioterapêutica:</label>
													<input class="form-control" type="text" ng-model="diag_prog.prescricao_fisioterapeutica" maxlength="254">
										        </div>
										    </div>

										    <div class="form-group row">
										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Colete:</label>
													<input class="form-control" type="text" ng-model="diag_prog.colete" maxlength="254">
										        </div>

										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Colete HS:</label>
													<input class="form-control" type="text" ng-model="diag_prog.colete_hs" numbers-only maxlength="9">
										        </div>

										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Idade do aparecimento:</label>
													<input class="form-control" type="text" ng-model="diag_prog.idade_aparecimento" numbers-only maxlength="9">
										        </div>

										        <div class="col-md-3 col-sm-3 col-xs-6">
										        	<label>Calço:</label>
													<input class="form-control" type="text" ng-model="diag_prog.calco" maxlength="254">
										        </div>
										    </div>

										    <div class="form-group row">
										        <div class="col-md-6 col-sm-6 col-xs-6">
										        	<label>Etiologia:</label>
													<input class="form-control" type="text" ng-model="diag_prog.etiologia" maxlength="254">
										        </div>

										        <div class="col-md-6 col-sm-6 col-xs-6">
										        	<label>Topografia:</label>
													<input class="form-control" type="text" ng-model="diag_prog.topografia" maxlength="254">
										        </div>
										    </div>

										    <uib-accordion>
											    <div uib-accordion-group class="panel-default" heading="Curva">
													<div class="form-group row forms-accordion">
											        	<div class="col-md-2 col-sm-3 col-xs-6">
											        		<label>Ordenação:</label>
															<input class="form-control" type="text" ng-model="curva.ordenacao" maxlength="254">
											        	</div>

											        	<div class="col-md-2 col-sm-3 col-xs-6">
											        		<label>Tipo:</label>
															<input class="form-control" type="text" ng-model="curva.tipo" maxlength="254">
											        	</div>

											        	<div class="col-md-2 col-sm-3 col-xs-6">
											        		<label>Ângulo de COBB:</label>
															<input class="form-control" type="text" ng-model="curva.angulo_cobb" numbers-only maxlength="9">
											        	</div>

											        	<div class="col-md-2 col-sm-3 col-xs-6">
											        		<label>Ângulo Ferguson:</label>
															<input class="form-control" type="text" ng-model="curva.angulo_ferguson" numbers-only maxlength="9">
											        	</div>

											        	<div class="col-md-2 col-sm-3 col-xs-6">
											        		<label>Grau de rotação:</label>
															<input class="form-control" type="text" ng-model="curva.grau_rotacao" numbers-only maxlength="9">
											        	</div>
											        </div>
												</div>

												<div uib-accordion-group class="panel-default" heading="Local Escoliose">
													<div class="form-group row forms-accordion">
											        	<div class="col-md-3 col-sm-3 col-xs-6">
											        		<label>Local:</label>
															<input class="form-control" type="text" ng-model="local_escoliose.local" maxlength="254">
											        	</div>

											        	<div class="col-md-3 col-sm-3 col-xs-6">
											        		<label>Lado:</label>
															<input class="form-control" type="text" ng-model="local_escoliose.lado" maxlength="254">
											        	</div>
											        </div>
												</div>

												<div uib-accordion-group class="panel-default" heading="Vértebra">
													<div class="form-group row forms-accordion">
											        	<div class="col-md-3 col-sm-3 col-xs-6">
											        		<label>Tipo</label>
															<input class="form-control" type="text" ng-model="vertebra.tipo" maxlength="254">
											        	</div>

											        	<div class="col-md-3 col-sm-3 col-xs-6">
											        		<label>Local:</label>
															<input class="form-control" type="text" ng-model="vertebra.local" maxlength="254">
											        	</div>

											        	<div class="col-md-3 col-sm-3 col-xs-6">
											        		<label>Altura:</label>
															<input class="form-control" type="text" ng-model="vertebra.altura" maxlength="254">
											        	</div>

											        	<div class="col-md-3 col-sm-3 col-xs-6">
											        		<label>Nome:</label>
															<input class="form-control" type="text" ng-model="vertebra.vertebra_nome" maxlength="254">
											        	</div>
											        </div>
												</div>
											</uib-accordion>

											<div class="form-group row">
										        <div class="padding-full-input">
													<label>HPP:</label>
													<textarea class="form-control" rows="4" ng-model="diag_prog.hpp"></textarea>
												</div>
										    </div>
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

		      		<div style="min-height: 25px">
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

		      		<div style="min-height: 25px">
		      			<span us-spinner="{radius:10, width:4, length: 8, color: '#2c97d1'}" spinner-on="showSpinnerTabelaAtendimentos"></span>
		      		</div>
		    	</div>
	    
			    <div class="modal-footer">
			        <button type="button" class="btn btn-link link-gray" data-dismiss="modal">Fechar</button>
			    </div>
	    	</div>
	  	</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="modalDadosPaciente">
	  	<div class="modal-dialog modal-lg" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="gridSystemModalLabel">Dados do paciente</h4>
	      		</div>

		      	<div class="modal-body">
		      		<span ng-if="!showSpinnerDadosPacientes && erroDadosPaciente">
		      			Erro ao excluir todos os usuários. Verifique sua conexão com a internet e tente novamente.
		      		</span>

		      		<div ng-if="!showSpinnerDadosPacientes && !erroDadosPaciente">
		      			<h3 class="title-form text-medium semi-bold p-b-s c-ic-blue upper">Geral</h3>

		      			<div class="form-group row row-dados-paciente">
				        	<div class="col-md-12 col-sm-12 col-xs-12">
				        		<label>Nome:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.nome ]]</p>
				        	</div>
				        </div>

				        <div class="form-group row row-dados-paciente">
				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>CPF:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.cpf ]]</p>
				        	</div>

				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>Identidade:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.identidade ]]</p>
				        	</div>

				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>Data de nascimento:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.data_nasc ]]</p>
				        	</div>
				        </div>

				        <div class="form-group row row-dados-paciente">
				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>Email:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.email ]]</p>
				        	</div>

				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>Médico:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.medico ]]</p>
				        	</div>

				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>Indicação:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.indicacao ]]</p>
				        	</div>
				        </div>

				        <h3 class="title-form text-medium semi-bold p-b-s c-ic-blue upper" ng-if="dadosPaciente.responsavel">Responsável</h3>

				        <div class="form-group row row-dados-paciente" ng-if="dadosPaciente.responsavel">
				        	<div class="col-md-6 col-sm-6 col-xs-6">
				        		<label>Nome:</label>
								<p class="form-control-static">[[ dadosPaciente.responsavel.nome ]]</p>
				        	</div>

				        	<div class="col-md-3 col-sm-3 col-xs-3">
				        		<label>CPF:</label>
								<p class="form-control-static">[[ dadosPaciente.responsavel.cpf ]]</p>
				        	</div>

				        	<div class="col-md-3 col-sm-3 col-xs-3">
				        		<label>Identidade:</label>
								<p class="form-control-static">[[ dadosPaciente.responsavel.identidade ]]</p>
				        	</div>
				        </div>

				        <div class="form-group row row-dados-paciente" ng-if="dadosPaciente.responsavel">
				        	<div class="col-md-6 col-sm-6 col-xs-6">
				        		<label>Email:</label>
								<p class="form-control-static">[[ dadosPaciente.responsavel.email ]]</p>
				        	</div>

				        	<div class="col-md-3 col-sm-3 col-xs-3">
				        		<label>Ocupação:</label>
								<p class="form-control-static">[[ dadosPaciente.responsavel.ocupacao ]]</p>
				        	</div>

				        	<div class="col-md-3 col-sm-3 col-xs-3">
				        		<label>Telefone:</label>
								<p class="form-control-static">[[ dadosPaciente.responsavel.telefone ]]</p>
				        	</div>
				        </div>

				        <h3 class="title-form text-medium semi-bold p-b-s c-ic-blue upper">Telefones</h3>

				        <div class="form-group row row-dados-paciente">
				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>Celular:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.celular ]]</p>
				        	</div>

				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>Casa:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.tel_res ]]</p>
				        	</div>

				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>Trabalho:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.tel_trab ]]</p>
				        	</div>
				        </div>

				        <h3 class="title-form text-medium semi-bold p-b-s c-ic-blue upper">Endereço</h3>

				        <div class="form-group row row-dados-paciente">
				        	<div class="col-md-12 col-sm-12 col-xs-12">
				        		<label>Endereço:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.end_res ]]</p>
				        	</div>
				        </div>

				        <div class="form-group row row-dados-paciente">
				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>CEP:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.cep ]]</p>
				        	</div>

				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>Cidade:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.cidade ]]</p>
				        	</div>

				        	<div class="col-md-4 col-sm-4 col-xs-4">
				        		<label>Estado:</label>
								<p class="form-control-static">[[ dadosPaciente.paciente.estado ]]</p>
				        	</div>
				        </div>
		      		</div>

		      		<div style="min-height: 150px" ng-if="showSpinnerDadosPacientes">
		      			<span us-spinner="{radius: 30, width: 8, length: 16, color: '#2c97d1'}" spinner-on="showSpinnerDadosPacientes"></span>
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
	