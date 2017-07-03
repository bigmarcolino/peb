'use strict';
var app = angular.module('peb', ['angularSpinner', '720kb.tooltips'], function($interpolateProvider, $qProvider) {
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

		cadastra: function(data){
			return $http.post('/api/pessoas', data);
		}
	}
});
 
app.controller('pebController', function($scope, apiService, $filter) {
	$scope.registerCpf;

	$scope.defaultSelectColor = true;

	$scope.changeDefaultSelectColor = function() {
		$scope.defaultSelectColor = false;
	}

	$scope.listarUsuarios = function() {
        $scope.showSpinner = true;

		apiService.listarUsuarios().then(function(response) {
            $scope.showSpinner = false;
			$scope.usuarios = response.data;
            $scope.filtrarUsuarios();
            $scope.ordenarUsuarios($scope.sortTypeUser);
        })
        .catch(function(response) {
            $scope.showSpinner = false;
            $('#modalErroUsuarios').modal('show');
        })
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

        apiService.excluirUsuarios(objCpfs).then(function(response) {
            $scope.showSpinnerExcluir = false;
            $('#modalErroExcluir').modal('hide');
            $scope.listarUsuarios();
            $scope.qtdUsuariosInativos();
        })
        .catch(function(response) {
            $scope.showSpinnerExcluir = false;
        })    
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
        var split = $scope.usuarioEdit.data_nasc.split("-");
        $scope.usuarioEdit.data_nasc = split[2] + "-" + split[1] + "-" + split[0];
    }

    $scope.salvarEdicaoUsuario = function () {
        var split = $scope.usuarioEdit.data_nasc.split("-");
        $scope.usuarioEdit.data_nasc = split[2] + "-" + split[1] + "-" + split[0];

        console.log($scope.usuarioEdit);

        apiService.editarUsuario($scope.usuarioEdit).then(function(res) {
            var index = _.findIndex($scope.usuariosFiltrados, function(o) { return o.cpf ==  res.data; });

            console.log($scope.usuarioEdit);            

            $scope.usuariosFiltrados[index].name = $scope.usuarioEdit.name;
            $scope.usuariosFiltrados[index].email = $scope.usuarioEdit.email;
            $scope.usuariosFiltrados[index].data_nasc = $scope.usuarioEdit.data_nasc;
            $scope.usuariosFiltrados[index].funcao = $scope.usuarioEdit.funcao;
            $scope.usuariosFiltrados[index].sexo = $scope.usuarioEdit.sexo;

            $scope.qtdUsuariosInativos();
            $scope.filtrarUsuarios();
            $scope.sortReverseUser = !$scope.sortReverseUser;
            $scope.ordenarUsuarios($scope.sortTypeUser);
        });
    }
});