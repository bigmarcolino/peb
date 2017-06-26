<div class="container-fluid main-container" ng-init="qtdUsuariosInativos(); listarUsuarios()">

	<div ng-if="showUsers" id="users-container">
		<h2 class="title-lg">Usuários</h2>

		<form action="" class="p-b-s form-filter-patients form-desktop form-search-input">
			<div class="form-group input-group-lg has-feedback">
				<input type="text" class="form-control" placeholder="Digite o nome, telefone ou CPF..." ng-model="searchUser">
				<span class="glyphicon glyphicon-search form-control-feedback"></span>
			</div>
		</form>

		<div class="form-patients p-s bg-white bd bd-gray bd-radius content-height-2">	
			<div class="pull-left form-group">
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

			<table class="table table-default table-list table-list-patients">
			    <thead>
			      	<tr>
			      		<th class="col-checkbox">
				            <label class="checkbox-default">
				            	<input type="checkbox">
				            	<span></span>
				            </label> 
				        </th>

				        <th ng-click="sortTypeUser = 'name'; sortReverseUser = !sortReverseUser" class="semi-bold col-lg-4 col-md-3">
				            <span>Nome</span> 
				        	<span ng-show="sortTypeUser == 'name' && !sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				            <span ng-show="sortTypeUser == 'name' && sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				        </th>

				        <th ng-click="sortTypeUser = 'cpf'; sortReverseUser = !sortReverseUser" class="semi-bold col-lg-1 hidden-sm hidden-xs">
				          	<span>Cpf</span> 
				            <span ng-show="sortTypeUser == 'cpf' && !sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				            <span ng-show="sortTypeUser == 'cpf' && sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				        </th>

				        <th ng-click="sortTypeUser = 'datanasc'; sortReverseUser = !sortReverseUser" class="semi-bold hidden-sm hidden-xs">
				          	<span>Data de Nascimento</span>
				            <span ng-show="sortTypeUser == 'datanasc' && !sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				            <span ng-show="sortTypeUser == 'datanasc' && sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				        </th>

				        <th ng-click="sortTypeUser = 'sexo'; sortReverseUser = !sortReverseUser" class="semi-bold col-lg-1 hidden-sm hidden-xs">
				          	<span>Sexo</span>
				            <span ng-show="sortTypeUser == 'sexo' && !sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				            <span ng-show="sortTypeUser == 'sexo' && sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				        </th>

				        <th ng-click="sortTypeUser = 'email'; sortReverseUser = !sortReverseUser" class="semi-bold col-lg-2 col-md-2 hidden-xs">
				          	<span>Email</span>
				            <span ng-show="sortTypeUser == 'email' && !sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				            <span ng-show="sortTypeUser == 'email' && sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				        </th>

				        <th ng-click="sortTypeUser = 'funcao'; sortReverseUser = !sortReverseUser" class="semi-bold col-lg-1 hidden-sm hidden-xs">
				          	<span>Função</span>
				            <span ng-show="sortTypeUser == 'funcao' && !sortReverseUser" class="glyphicon glyphicon-chevron-down"></span>
				            <span ng-show="sortTypeUser == 'funcao' && sortReverseUser" class="glyphicon glyphicon-chevron-up"></span>
				        </th>

				        <th class="semi-bold col-edit"></th>
			      	</tr>
			    </thead>
			    
			    <tbody>
				    <tr ng-repeat="usuario in usuarios | orderBy:sortTypeUser:sortReverseUser | filter:searchUser">
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
				        <td>[[ usuario.cpf ]]</td>
				        <td>[[ usuario.data_nasc ]]</td>
				        <td>[[ usuario.sexo ]]</td>
				        <td>[[ usuario.email ]]</td>
				        <td>[[ usuario.funcao ]]</td>
				        <td class="text-right">
				        	<a href="" class="btn btn-white-blue btn-xs edit-patient-button">
				        		<span class="glyphicon glyphicon-pencil"></span>
				        	</a>
				        </td>
				    </tr>
			    </tbody>
			</table>

			<div class="total-count text-small">
				<span>[[ usuarios.length ]]</span>
				<span> resultado</span><span ng-if="usuarios.length != 1">s</span>
			</div>
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
			        <button type="button" class="btn btn-red upper btn-loading confirm-remove-btn btn-loading" data-dismiss="modal" ng-click="excluirTodosUsuarios()">Sim</button>
			    </div>
	    	</div>
	  	</div>
	</div>
</div>