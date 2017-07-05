'use strict';
var app = angular.module('peb', ['angularSpinner', '720kb.tooltips', 'ae-datetimepicker'], function($interpolateProvider, $qProvider) {
  $interpolateProvider.startSymbol('[[');
  $interpolateProvider.endSymbol(']]');
  $qProvider.errorOnUnhandledRejections(false);
});

app.directive('numbersOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9]/g, '');

                    if (transformedInput !== text) {
                        ngModelCtrl.$setViewValue(transformedInput);
                        ngModelCtrl.$render();
                    }
                
                    return transformedInput;
                }

                return undefined;
            }            

            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});

app.filter('tracos', function() {
    return function(input) {
        if(input == "") {
            return "---";
        }
        else {
            return input;
        }
    }
});

app.filter('dateBr', function() {
    return function(input) {
        return input.split('-')[2] + '-' + input.split('-')[1] + '-' + input.split('-')[0];
    }
});

app.factory('apiService', function($http) {
	return {
		listarUsuarios: function(){
			return $http.get('/usuario/listarUsuarios');
		},

        qtdUsuariosInativos: function(){
            return $http.get('/usuario/qtdUsuariosInativos');
        },

        excluirUsuarios: function(cpfs) {
            return $http.post('/usuario/excluirUsuarios', cpfs);
        },

        editarUsuario: function(usuario){
            var cpf = usuario.cpf;
            delete usuario.cpf;
            return $http.put('/usuario/editarUsuario/' + cpf, usuario);
        },

        usuarioLogado: function(cpf){
            return $http.get('/usuario/usuarioLogado/' + cpf);
        },

		cadastra: function(data){
			return $http.post('/api/pessoas', data);
		}
	}
});
 
app.controller('pebController', function($scope, apiService, $filter, $timeout) {
	$scope.registerCpf;

	$scope.defaultSelectColor = true;

	$scope.changeDefaultSelectColor = function() {
		$scope.defaultSelectColor = false;
	}

	$scope.listarUsuarios = function() {
        $scope.showSpinnerUsuarios = true;

        $timeout( function(){
            apiService.listarUsuarios().then(function(response) {
                $scope.showSpinnerUsuarios = false;
                $scope.usuarios = response.data;
                $scope.filtrarUsuarios();
                $scope.ordenarUsuarios($scope.sortTypeUser);
            })
            .catch(function(response) {
                $scope.showSpinnerUsuarios = false;
                $('#modalErroUsuarios').modal('show');
            })
        }, 1000 );
	}

    $scope.qtdUsuariosInativos = function() {
        apiService.qtdUsuariosInativos().then(function(response) {
            $scope.countUsuariosInativos = response.data;
        });
    }

    $scope.excluirUsuarios = function() {
        $scope.showSpinnerExcluir = true;
        $('#modalErroExcluir').modal('show');

        var cpfs = [];

        for(var i = 0; i < $scope.usuariosFiltrados.length; i++) {
            if($scope.usuariosFiltrados[i].checked) { 
                cpfs.push($scope.usuariosFiltrados[i].cpf);
            }
        }

        var objCpfs = cpfs.reduce(function(acc, cur, i) {
          acc[i] = cur;
          return acc;
        }, {});

        $timeout( function(){
            apiService.excluirUsuarios(objCpfs).then(function(response) {
                $('#modalErroExcluir').modal('hide');

                $scope.qtdUsuariosInativos();
                $scope.checkboxSelecionarTodos = false;
                
                cpfs.forEach(function(cpf) {
                    var index = _.findIndex($scope.usuariosFiltrados, function(o) { return o.cpf == cpf; });
                    $scope.usuariosFiltrados.splice(index, 1);
                });

                $scope.atualizarPagerUsuarios(1);
                $scope.showSpinnerExcluir = false;
            })
            .catch(function(response) {
                $scope.showSpinnerExcluir = false;
            })
        }, 1000 );    
    }

	$scope.sortTypeUser = 'funcao';
	$scope.sortReverseUser = false;
	$scope.searchUser = '';

	$scope.showUsers = false;
	$scope.showPacientes = true;

	$scope.togglePaginas = function(pagina) {
		if (pagina == 'usuarios') {
			$scope.showUsers = true;
			$scope.showPacientes = false;
		}
		else if (pagina == 'pacientes') {
			$scope.showUsers = false;
			$scope.showPacientes = true;
		}
	}

    $scope.cpfLogged = function() {
        return angular.element( document.querySelector( '#logged' ) )[0].innerText.trim();
    }

    $scope.checkBoxSelecionarUsuarios = false;

    $scope.selecionarTodosUsuarios = function() {
        $scope.checkBoxSelecionarUsuarios = !$scope.checkBoxSelecionarUsuarios;

        if($scope.checkBoxSelecionarUsuarios) {
            for(var i = 0; i < $scope.usuariosFiltrados.length; i++) {
                if($scope.usuariosFiltrados[i].cpf != $scope.cpfLogged()) {
                    $scope.usuariosFiltrados[i].checked = true;
                }
            }
        }
        else {
            for(var i = 0; i < $scope.usuariosFiltrados.length; i++) {
                $scope.usuariosFiltrados[i].checked = false;
            }
        }
    }

    var defaultPageSize = 3;
    $scope.pageSize = defaultPageSize;

    $scope.pagerUsuario = function(array, currentPage) {
        var totalItems = array.length;
        var totalPages = Math.ceil(totalItems / $scope.pageSize);
        var startPage, endPage;

        if (totalPages <= 10) {
            // less than 10 total pages so show all
            startPage = 1;
            endPage = totalPages;
        }
        else {
            // more than 10 total pages so calculate start and end pages
            if (currentPage <= 6) {
                startPage = 1;
                endPage = 10;
            } else if (currentPage + 4 >= totalPages) {
                startPage = totalPages - 9;
                endPage = totalPages;
            } else {
                startPage = currentPage - 5;
                endPage = currentPage + 4;
            }
        }
 
        // calculate start and end item indexes
        var startIndex = (currentPage - 1) * $scope.pageSize;
        var endIndex = Math.min(startIndex + $scope.pageSize - 1, totalItems - 1);
 
        // create an array of pages to ng-repeat in the pager control
        var pages = _.range(startPage, endPage + 1);

        return {
            totalItems: totalItems,
            currentPage: currentPage,
            totalPages: totalPages,
            startPage: startPage,
            endPage: endPage,
            startIndex: startIndex,
            endIndex: endIndex,
            pages: pages
        };
    }

    $scope.atualizarPagerUsuarios = function(page) {
        $scope.pageSize = defaultPageSize;
        
        $scope.pagerObjectUsuarios = $scope.pagerUsuario($scope.usuariosFiltrados, page);

        if ($scope.pagerObjectUsuarios.currentPage == $scope.pagerObjectUsuarios.totalPages) {
            $scope.pageSize = $scope.usuariosFiltrados.length - $scope.pageSize*($scope.pagerObjectUsuarios.totalPages-1);
        }
    }

    $scope.filtrarUsuarios = function() {
        $scope.usuariosFiltrados = $filter('filter')($scope.usuarios, this.searchUser);
        $scope.atualizarPagerUsuarios(1);
    }

    $scope.ordenarUsuarios = function (sortType) {
        $scope.sortReverseUser = !$scope.sortReverseUser;
        $scope.sortTypeUser = sortType;
        $scope.usuariosFiltrados = $filter('orderBy')($scope.usuariosFiltrados, sortType, $scope.sortReverseUser);
    }

    $scope.setUsuarioEdit = function(usuario) {
        $scope.usuarioEdit = angular.copy(usuario);
    }

    $scope.salvarEdicaoUsuario = function () {
        $scope.showSpinnerEditarUsuario = true;
        $('#modalErroEditarUsuario').modal('show');

        $scope.usuarioEdit.data_nasc = $scope.usuarioEdit.data_nasc.format("YYYY-MM-DD").toString();

        if($scope.cpfLogged() == $scope.usuarioEdit.cpf) {
            $scope.usuarioLogado = $scope.usuarioEdit.name;
        }

        apiService.editarUsuario($scope.usuarioEdit).then(function(res) {
            $timeout( function() {
                $('#modalErroEditarUsuario').modal('hide');

                var index = _.findIndex($scope.usuariosFiltrados, function(o) { return o.cpf == res.data; });           

                $scope.usuariosFiltrados[index].name = $scope.usuarioEdit.name;
                $scope.usuariosFiltrados[index].email = $scope.usuarioEdit.email;
                $scope.usuariosFiltrados[index].data_nasc = $scope.usuarioEdit.data_nasc.format("YYYY-MM-DD").toString();
                $scope.usuariosFiltrados[index].funcao = $scope.usuarioEdit.funcao;
                $scope.usuariosFiltrados[index].sexo = $scope.usuarioEdit.sexo;

                $scope.qtdUsuariosInativos();
                $scope.filtrarUsuarios();
                $scope.sortReverseUser = !$scope.sortReverseUser;
                $scope.ordenarUsuarios($scope.sortTypeUser);

                $scope.showSpinnerEditarUsuario = false;
            }, 1000 );
        })
        .catch(function(res) {
            $timeout( function() {
                $scope.showSpinnerEditarUsuario = false;
            }, 1000 );
        })
    }

    $scope.dpEditarUsuarioOptions = {
        format: 'DD-MM-YYYY',
        maxDate: moment().subtract(18, 'years'),
        widgetPositioning: {vertical: 'bottom', horizontal: 'auto'},
        ignoreReadonly: true
    }

    $scope.dpRegistrarsUsuarioOptions = {
        format: 'DD-MM-YYYY',
        maxDate: moment().subtract(18, 'years'),
        widgetPositioning: {vertical: 'top', horizontal: 'auto'},
        ignoreReadonly: true,
        useCurrent: false
    }

    $scope.getUsuarioLogado = function () {
        var cpf = angular.element( document.querySelector( '#logged' ) )[0].innerText.trim();

        apiService.usuarioLogado(cpf).then(function(response) {
            $scope.usuarioLogado = response.data.nome.name.split(" ")[0];
        });
    }

    $scope.checkEmailEditarUsuario = function() {
        if($scope.usuarioEdit.email == '') {
            $scope.emailVazioEditarUsuario = true;
        }
        else {
            $scope.emailVazioEditarUsuario = false;
        }
    }

    $scope.checkNomeEditarUsuario = function() {
        if($scope.usuarioEdit.name == '') {
            $scope.nomeVazioEditarUsuario = true;
        }
        else {
            $scope.nomeVazioEditarUsuario = false;
        }
    }

    $scope.checkEmailExistenciaEditarUsuario = function() {
        var index = _.findIndex($scope.usuariosFiltrados, function(o) { return o.email == $scope.usuarioEdit.email; });

        if(index == -1) {
            $scope.emailExisteEditarUsuario = false;
        }
        else {
            $scope.emailExisteEditarUsuario = true;
        }
    }
});