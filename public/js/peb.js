'use strict';
var app = angular.module('peb', [], function($interpolateProvider) {
  $interpolateProvider.startSymbol('[[');
  $interpolateProvider.endSymbol(']]');
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

app.factory('apiService', function($http) {
	return {
		listarUsuarios: function(){
			return $http.get('/usuario/listarUsuarios');
		},

        qtdUsuariosInativos: function(){
            return $http.get('/usuario/qtdUsuariosInativos');
        },

        excluirUsuario: function(cpf){
            return $http.delete('/usuario/excluirUsuario/' + cpf)
        },

		cadastra: function(data){
			return $http.post('/api/pessoas', data);
		},

		edita: function(data){
			var id = data.id;
			delete data.id;
			return $http.put('/api/pessoa/'+id, data);
		}
	}
});
 
app.controller('pebController', function($scope, apiService) {
	$scope.registerCpf;

	$scope.defaultSelectColor = true;

	$scope.changeDefaultSelectColor = function() {
		$scope.defaultSelectColor = false;
	}

	$scope.listarUsuarios = function() {
		apiService.listarUsuarios().then(function(response) {
			$scope.usuarios = response.data;
        });
	}

    $scope.qtdUsuariosInativos = function() {
        apiService.qtdUsuariosInativos().then(function(response) {
            $scope.countUsuariosInativos = response.data;
        });
    }

    $scope.excluirUsuario = function(cpf) {
        apiService.excluirUsuario(cpf).then(function(response) {
            return response.data;
        });
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

    $scope.excluirTodosUsuarios = function() {
        for(var i = 0; i < $scope.usuarios.length; i++) {
            if($scope.usuarios[i].checked) {
                $scope.excluirUsuario($scope.usuarios[i].cpf);
                $scope.usuarios.splice(i, 1);
            }
        }

        $scope.qtdUsuariosInativos();
    }
});