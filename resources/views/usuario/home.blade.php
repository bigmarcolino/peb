@extends('usuario.layout.auth')

@section('content')

<div class="container-fluid main-container" ng-init="qtdUsuariosInativos(); listarUsuarios()">

	<div ng-if="showUsers" id="users-container">
		<h2 class="title-lg">Usuários</h2>

		<form action="" class="p-b-s form-filter-patients form-desktop form-search-input">
			<div class="form-group input-group-lg has-feedback">
				<input type="text" class="form-control" placeholder="Digite o nome, telefone ou CPF..." ng-model="searchUser" ng-change="filtrarUsuarios()">
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
				            	<input type="checkbox" ng-checked="checkboxSelecionarTodos" ng-click="selecionarTodosUsuarios()">
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
				    <tr ng-repeat="usuario in usuariosFiltrados | limitTo: pagerObjectUsuarios.currentPage*pageSize | limitTo: pageSize*(-1)">
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

			<ul ng-if="usuariosFiltrados.length > pageSize" class="pagination">
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

	<div ng-if="showPacientes" id="pacientes-container">
		<h2 class="title-lg">Pacientes</h2>

		<form action="" class="p-b-s form-filter-patients form-desktop form-search-input">
			<div class="form-group input-group-lg has-feedback">
				<input type="text" class="form-control" placeholder="Digite o nome, código, telefone ou CPF..." ng-model="searchUser">
				<span class="glyphicon glyphicon-search form-control-feedback"></span>
			</div>
		</form>

		<div class="form-patients p-s bg-white bd bd-gray bd-radius content-height-2">
			<div class="pull-right">
				<a class="btn btn-green btn-sm">ADICIONAR</a>
			</div>

			<table class="table table-default table-list table-list-patients">
			    <thead>
			      	<tr>
			      		<th class="col-checkbox">
				            <label class="checkbox-default">
				            	<input type="checkbox">
				            	<span></span>
				            </label> 
				        </th>

				        <th ng-click="sortTypeUser = 'name'; sortReverseUser = !sortReverseUser" class="semi-bold">
				            <span>Nome</span> 
				        	<span ng-show="sortTypeUser == 'name' && !sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				            <span ng-show="sortTypeUser == 'name' && sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				        </th>

				        <th ng-click="sortTypeUser = 'cpf'; sortReverseUser = !sortReverseUser" class="semi-bold">
				          	<span>Cpf</span> 
				            <span ng-show="sortTypeUser == 'cpf' && !sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				            <span ng-show="sortTypeUser == 'cpf' && sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				        </th>

				        <th ng-click="sortTypeUser = 'datanasc'; sortReverseUser = !sortReverseUser" class="semi-bold">
				          	<span>Data de Nascimento</span>
				            <span ng-show="sortTypeUser == 'datanasc' && !sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				            <span ng-show="sortTypeUser == 'datanasc' && sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				        </th>
			      	</tr>
			    </thead>
			    
			    <tbody>
				    <tr ng-repeat="usuario in usuarios | orderBy:sortTypeUser:sortReverseUser | filter:searchUser | limitTo: 9 | limitTo: -4">
				        <td>
				        	<label class="checkbox-default">
				            	<input type="checkbox">
				            	<span></span>
				            </label>
				        </td>
				        <td>[[ usuario.name ]]</td>
				        <td>[[ usuario.cpf ]]</td>
				        <td>[[ usuario.data_nasc ]]</td>
				    </tr>
			    </tbody>
			</table>

			<span us-spinner="{radius:30, width:8, length: 16, color: '#2c97d1'}" spinner-on="showSpinner"></span>
		</div>
	</div>

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
		      			Erro ao excluir todos os usuários. Verifique sua conexão com a internet e recarregue a página.
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
		      		Erro ao carregar usuários. Verifique sua conexão com a internet e recarregue a página.
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
		      		Erro ao carregar usuários. Verifique sua conexão com a internet e recarregue a página.
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
		      			Erro ao editar o usuário. Verifique sua conexão com a internet e recarregue a página.
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
</div>

@endsection
