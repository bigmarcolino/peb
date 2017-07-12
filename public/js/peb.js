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
		listarUsuariosPacientes: function(){
			return $http.get('/usuario/listarUsuariosPacientes');
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

		addPaciente: function(paciente){
			return $http.post('/usuario/addPaciente', paciente);
		},

        excluirPacientes: function(cpfs) {
            return $http.post('/usuario/excluirPacientes', cpfs);
        }
	}
});
 
app.controller('pebController', function($scope, apiService, $filter, $timeout) {
	$scope.registerCpf;

	$scope.defaultSelectColor = true;

	$scope.changeDefaultSelectColor = function() {
		$scope.defaultSelectColor = false;
	}

	$scope.listarUsuariosPacientes = function() {
        $scope.showSpinnerUsuarios = true;

        $timeout( function(){
            apiService.listarUsuariosPacientes().then(function(response) {
                $scope.showSpinnerUsuarios = false;

                $scope.usuarios = response.data.usuarios;
                $scope.filtrarUsuarios();
                $scope.ordenarUsuarios($scope.sortTypeUser);

                $scope.pacientes = response.data.pacientes;
                $scope.filtrarPacientes();
                $scope.ordenarPacientes($scope.sortTypePaciente);
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
                $scope.checkboxSelecionarUsuarios = false;
                
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

    $scope.showUsuarios = false;
    $scope.showPacientes = true;
    $scope.showAddPacientes = false;

    $scope.togglePaginas = function(pagina) {
        if (pagina == 'usuarios') {
            $scope.showUsuarios = true;
            $scope.showPacientes = false;
            $scope.showAddPacientes = false;
        }
        else if (pagina == 'pacientes') {
            $scope.showUsuarios = false;
            $scope.showPacientes = true;
            $scope.showAddPacientes = false;
        }
        else if (pagina == 'addPacientes') {
            $scope.showUsuarios = false;
            $scope.showPacientes = false;
            $scope.showAddPacientes = true;
            $scope.novoPaciente = {};
            $scope.dataVazioAddPaciente = undefined;
            $scope.nomeVazioAddPaciente = undefined;
            $scope.cpfVazioAddPaciente = undefined;
        }
    }

    $scope.cpfLogged = function() {
        return angular.element( document.querySelector( '#logged' ) )[0].innerText.trim();
    }

    $scope.checkboxSelecionarUsuarios = false;

    $scope.selecionarTodosUsuarios = function() {
        $scope.checkboxSelecionarUsuarios = !$scope.checkboxSelecionarUsuarios;

        if($scope.checkboxSelecionarUsuarios) {
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
    $scope.pageSizeUsuarios = defaultPageSize;
    $scope.pageSizePacientes = defaultPageSize;

    $scope.pager = function(array, currentPage, pageSize) {
        var totalItems = array.length;
        var totalPages = Math.ceil(totalItems / pageSize);
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
        var startIndex = (currentPage - 1) * pageSize;
        var endIndex = Math.min(startIndex + pageSize - 1, totalItems - 1);
 
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
        $scope.pageSizeUsuarios = defaultPageSize;
        
        $scope.pagerObjectUsuarios = $scope.pager($scope.usuariosFiltrados, page, $scope.pageSizeUsuarios);

        if ($scope.pagerObjectUsuarios.currentPage == $scope.pagerObjectUsuarios.totalPages) {
            $scope.pageSizeUsuarios = $scope.usuariosFiltrados.length - $scope.pageSizeUsuarios*($scope.pagerObjectUsuarios.totalPages-1);
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

    $scope.dpNovoPacienteOptions = {
        format: 'DD-MM-YYYY',
        maxDate: moment(),
        widgetPositioning: {vertical: 'bottom', horizontal: 'auto'},
        ignoreReadonly: true,
        useCurrent: false
    }

    $scope.addPaciente = function(option) {
        if($scope.dataVazioAddPaciente == undefined) {
            $scope.dataVazioAddPaciente = true;
        }

        if($scope.nomeVazioAddPaciente == undefined) {
            $scope.nomeVazioAddPaciente = true;
        }
        
        if($scope.cpfVazioAddPaciente == undefined) {
            $scope.cpfVazioAddPaciente = true;
        }

        if(!$scope.dataVazioAddPaciente && !$scope.nomeVazioAddPaciente && !$scope.cpfVazioAddPaciente) {
            $scope.showSpinnerAddPaciente = true;
            $('#modalAddPaciente').modal('show');

            $scope.novoPaciente.data_nasc = $scope.novoPaciente.data_nasc.format("YYYY-MM-DD").toString();

            $timeout( function() {
                apiService.addPaciente($scope.novoPaciente).then(function(response) {
                    $('#modalAddPaciente').modal('hide');

                    if(option) {
                        $scope.novoPaciente = {};
                    }
                    else {
                        $scope.togglePaginas('pacientes');
                    }
                })
                .catch(function(response) {
                    $scope.showSpinnerAddPaciente = false;
                })
            }, 1000 );
        }        
    }

    $scope.sortTypePaciente = 'nome';
    $scope.sortReversePaciente = false;
    $scope.searchPaciente = '';

    $scope.atualizarPagerPacientes = function(page) {
        $scope.pageSizePacientes = defaultPageSize;
        
        $scope.pagerObjectPacientes = $scope.pager($scope.pacientesFiltrados, page, $scope.pageSizePacientes);

        if ($scope.pagerObjectPacientes.currentPage == $scope.pagerObjectPacientes.totalPages) {
            $scope.pageSizePacientes = $scope.pacientesFiltrados.length - $scope.pageSizePacientes*($scope.pagerObjectPacientes.totalPages-1);
        }
    }

    $scope.filtrarPacientes = function() {
        $scope.pacientesFiltrados = $filter('filter')($scope.pacientes, this.searchPaciente);
        $scope.atualizarPagerPacientes(1);
    }

    $scope.ordenarPacientes = function (sortType) {
        $scope.sortReversePaciente = !$scope.sortReversePaciente;
        $scope.sortTypePaciente = sortType;
        $scope.pacientesFiltrados = $filter('orderBy')($scope.pacientesFiltrados, sortType, $scope.sortReversePaciente);
    }

    $scope.excluirPacientes = function() {
        $scope.showSpinnerExcluir = true;
        $('#modalErroExcluir').modal('show');

        var cpfs = [];

        for(var i = 0; i < $scope.pacientesFiltrados.length; i++) {
            if($scope.pacientesFiltrados[i].checked) { 
                cpfs.push($scope.pacientesFiltrados[i].cpf);
            }
        }

        var objCpfs = cpfs.reduce(function(acc, cur, i) {
          acc[i] = cur;
          return acc;
        }, {});

        apiService.excluirPacientes(objCpfs).then(function(response) {
            $('#modalErroExcluir').modal('hide');

            $scope.checkboxSelecionarPacientes = false;
            
            cpfs.forEach(function(cpf) {
                var index = _.findIndex($scope.pacientesFiltrados, function(o) { return o.cpf == cpf; });
                $scope.pacientesFiltrados.splice(index, 1);
            });

            $scope.atualizarPagerPacientes(1);
            $scope.showSpinnerExcluir = false;
        })
        .catch(function(response) {
            $scope.showSpinnerExcluir = false;
        })
    }

    $scope.checkboxSelecionarPacientes = false;

    $scope.selecionarTodosPacientes = function() {
        $scope.checkboxSelecionarPacientes = !$scope.checkboxSelecionarPacientes;

        if($scope.checkboxSelecionarPacientes) {
            for(var i = 0; i < $scope.pacientesFiltrados.length; i++) {
                $scope.pacientesFiltrados[i].checked = true;
            }
        }
        else {
            for(var i = 0; i < $scope.pacientesFiltrados.length; i++) {
                $scope.pacientesFiltrados[i].checked = false;
            }
        }
    }

    $scope.checkDataAddPaciente = function() {
        if($scope.novoPaciente.data_nasc == '') {
            $scope.dataVazioAddPaciente = true;
        }
        else {
            $scope.dataVazioAddPaciente = false;
        }
    }

    $scope.checkNomeAddPaciente = function() {
        if($scope.novoPaciente.nome == '') {
            $scope.nomeVazioAddPaciente = true;
        }
        else {
            $scope.nomeVazioAddPaciente = false;
        }
    }

    $scope.checkCpfAddPaciente = function() {
        if($scope.novoPaciente.cpf == '' || $scope.novoPaciente.cpf == undefined) {
            $scope.cpfVazioAddPaciente = true;
        }
        else {
            $scope.cpfVazioAddPaciente = false;
        }
    }

    $scope.checkCpfExistenciaAddPaciente = function() {
        var index = _.findIndex($scope.usuariosFiltrados, function(o) { return o.email == $scope.usuarioEdit.email; });

        if(index == -1) {
            $scope.emailExisteEditarUsuario = false;
        }
        else {
            $scope.emailExisteEditarUsuario = true;
        }
    }
});