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

app.directive("floatingNumberOnly", function() {
    return {
        require: 'ngModel',
        link: function(scope, ele, attr, ctrl) {

            ctrl.$parsers.push(function(inputValue) {
                var pattern = new RegExp("(^[0-9]{1,9})+(\.[0-9]{1,4})?$", "g");
                
                if (inputValue == '')
                    return '';
        
                var dotPattern = /^[.]*$/;

                if (dotPattern.test(inputValue)) {
                    ctrl.$setViewValue('');
                    ctrl.$render();
                    return '';
                }

                var newInput = inputValue.replace(/[^0-9.]/g, '');

                if (newInput != inputValue) {
                    ctrl.$setViewValue(newInput);
                    ctrl.$render();
                }

                var result;
                var dotCount;
                var newInputLength = newInput.length;
                
                if (result = (pattern.test(newInput))) {
                    dotCount = newInput.split(".").length - 1;
                    
                    if (dotCount == 0 && newInputLength > 9) { 
                        newInput = newInput.slice(0, newInputLength - 1);
                        ctrl.$setViewValue(newInput);
                        ctrl.$render();
                    }
                } else {              
                    dotCount = newInput.split(".").length - 1;
                  
                    if (newInputLength > 0 && dotCount > 1) {
                        newInput = newInput.slice(0, newInputLength - 1);
                    }

                    if ((newInput.slice(newInput.indexOf(".") + 1).length) > 4) {
                        newInput = newInput.slice(0, newInputLength - 1);
                    }
                    
                    ctrl.$setViewValue(newInput);
                    ctrl.$render();
                }

                return newInput;
            });
        }
    };
});

app.filter('tracos', function() {
    return function(input) {
        if(input == "" || input == undefined || input == null) {
            return "---";
        }
        else {
            return input;
        }
    }
});

app.filter('dateBr', function() {
    return function(input) {
        if(input != undefined && input != null) {
            return input.toString().split('-')[2] + '-' + input.toString().split('-')[1] + '-' + input.toString().split('-')[0];
        }
    }
});

app.filter("localeOrderBy", [function () {
    return function (array, sortPredicate, reverseOrder) {
        if (!Array.isArray(array)) return array;
        if (!sortPredicate) return array;

        var isString = function (value) {
            return (typeof value === "string");
        };

        var isNumber = function (value) {
            return (typeof value === "number");
        };

        var isBoolean = function (value) {
            return (typeof value === "boolean");
        };

        var arrayCopy = [];
        angular.forEach(array, function (item) {
            arrayCopy.push(item);
        });

        arrayCopy.sort(function (a, b) {
            var valueA = a[sortPredicate];
            var valueB = b[sortPredicate];

            if (isString(valueA))
                return !reverseOrder ? valueA.localeCompare(valueB) : valueB.localeCompare(valueA);

            if (isNumber(valueA) || isBoolean(valueA))
                return !reverseOrder ? valueA - valueB : valueB - valueA;

            return 0;
        });

        return arrayCopy;
    }
}]);

app.filter("filterIgnoringAccents", function() {
    var resolverAcentuacao = function resolverAcentuacao(string) {
        var regex = /[çÇáàâäãÁÀÂÄÃéèêëÉÈÊËíìîïÍÌÎÏóòôöõÓÒÔÖÕúùûüÚÙÛÜ]/g;

        var translate = {
            "á": "a", "à": "a", "â": "a", "ä": "a", "ã": "a", "Á": "A", "À": "A", "Â": "A", "Ä": "A", "Ã": "A",
            "é": "e", "è": "e", "ê": "e", "ë": "e", "É": "E", "È": "E", "Ê": "E", "Ë": "E",
            "í": "i", "ì": "i", "î": "i", "ï": "i", "Í": "I", "Ì": "I", "Î": "I", "Ï": "I",
            "ó": "o", "ò": "o", "ô": "o", "ö": "o", "õ": "o", "Ó": "O", "Ò": "O", "Ô": "O", "Ö": "O", "Õ": "Õ",
            "ú": "u", "ù": "u", "û": "u", "ü": "u", "Ú": "U", "Ù": "U", "Û": "U", "Ü": "U",
            "ç": "c", "Ç": "C"
        };

        return (
            string.replace(regex, function(match) {
                return translate[match];
            })
        );
    }

    return function(array, input) {
        var arrayFiltrado = [];

        if (input == undefined) {
            return array;
        }
        else if (input.toString() == "") {
            return array;
        }
        else {
            for(var i = 0; i < array.length; i++) {
                _.forOwn(array[i], function(value, key) {
                    if(value != null && value != undefined && value != "") {
                        var clearedValue = resolverAcentuacao(value.toString().toLowerCase());
                        var clearedInput = resolverAcentuacao(input.toLowerCase());

                        if(clearedValue.indexOf(clearedInput) != -1){
                            arrayFiltrado.push(array[i]);
                        }
                    }
                });
            }
        }

        return arrayFiltrado;
    }
});

app.factory('apiService', function($http) {
	return {
		listarUsuariosPacientes: function() {
			return $http.get('/usuario/listarUsuariosPacientes');
		},

        qtdUsuariosInativos: function() {
            return $http.get('/usuario/qtdUsuariosInativos');
        },

        excluirUsuarios: function(cpfs) {
            return $http.post('/usuario/excluirUsuarios', cpfs);
        },

        editarUsuario: function(usuario) {
            return $http.put('/usuario/editarUsuario', usuario);
        },

        usuarioLogado: function(cpf) {
            return $http.get('/usuario/usuarioLogado/' + cpf);
        },

		addPaciente: function(paciente) {
			return $http.post('/usuario/addPaciente', paciente);
		},

        excluirPacientes: function(cpfs) {
            return $http.post('/usuario/excluirPacientes', cpfs);
        },

        getPacienteEdit: function(cpf) {
            return $http.get('/usuario/getPacienteEdit/' + cpf);
        },

        editarPaciente: function(paciente) {
            return $http.put('/usuario/editarPaciente', paciente);
        },

        addAtendimento: function(cpf, dados) {
            return $http.post('/usuario/addAtendimento/' + cpf, dados);
        },

        getAtendimentos: function(cpf, offset) {
            return $http.get('/usuario/getAtendimentos/' + cpf + '/' + offset);
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

                document.documentElement.style.backgroundColor = '#efefef';
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
        $scope.showSpinnerExcluirUsuarios = true;
        $('#modalErroExcluirUsuarios').modal('show');

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
                $('#modalErroExcluirUsuarios').modal('hide');

                $scope.qtdUsuariosInativos();
                $scope.checkboxSelecionarUsuarios = false;
                
                cpfs.forEach(function(cpf) {
                    var index = _.findIndex($scope.usuarios, function(o) { return o.cpf == cpf; });
                    $scope.usuarios.splice(index, 1);
                });

                $scope.filtrarUsuarios();
                $scope.sortReverseUser = !$scope.sortReverseUser;
                $scope.ordenarUsuarios($scope.sortTypeUser);
            })
            .catch(function(response) {
                $scope.showSpinnerExcluirUsuarios = false;
            })
        }, 1000 );    
    }

	$scope.sortTypeUser = 'funcao';
	$scope.sortReverseUser = false;
	$scope.searchUser = '';

    $scope.showPacientes = true;

    $scope.togglePaginas = function(pagina) {
        if (pagina == 'usuarios') {
            $scope.showUsuarios = true;
            $scope.showPacientes = false;
            $scope.showAddPacientes = false;
            $scope.showEditPacientes = false;
            $scope.showViewPacientes = false;
        }
        else if (pagina == 'pacientes') {
            $scope.showUsuarios = false;
            $scope.showPacientes = true;
            $scope.showAddPacientes = false;
            $scope.showEditPacientes = false;
            $scope.showViewPacientes = false;
        }
        else if (pagina == 'addPacientes') {
            $scope.showUsuarios = false;
            $scope.showPacientes = false;
            $scope.showAddPacientes = true;
            $scope.showEditPacientes = false;
            $scope.showViewPacientes = false;

            $scope.novoPaciente = {};
            $scope.dataVazioPaciente = undefined;
            $scope.nomeVazioPaciente = undefined;
            $scope.cpfVazioPaciente = undefined;
        }
        else if (pagina == 'editPaciente') {
            $scope.showUsuarios = false;
            $scope.showPacientes = false;
            $scope.showAddPacientes = false;
            $scope.showEditPacientes = true;
            $scope.showViewPacientes = false;

            $scope.dataVazioPaciente = undefined;
            $scope.nomeVazioPaciente = undefined;
            $scope.cpfVazioPaciente = undefined;
        }
        else if (pagina == 'viewPaciente') {
            $scope.showUsuarios = false;
            $scope.showPacientes = false;
            $scope.showAddPacientes = false;
            $scope.showEditPacientes = false;
            $scope.showViewPacientes = true;

            $scope.resetAtendimento();
            $scope.toggleAtendimento('resumo');
        
            $scope.showIniciarAtendimento = true;
            $scope.showFinalizarAtendimento = false;
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

    var defaultPageSize = 20;
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
        $scope.usuariosFiltrados = $filter('filterIgnoringAccents')($scope.usuarios, this.searchUser);
        $scope.atualizarPagerUsuarios(1);
    }

    $scope.ordenarUsuarios = function (sortType) {
        $scope.sortReverseUser = !$scope.sortReverseUser;
        $scope.sortTypeUser = sortType;
        $scope.usuariosFiltrados = $filter('localeOrderBy')($scope.usuariosFiltrados, sortType, $scope.sortReverseUser);
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

                var index = _.findIndex($scope.usuariosFiltrados, function(o) { return o.cpf == $scope.usuarioEdit.cpf; });           

                $scope.usuariosFiltrados[index].name = $scope.usuarioEdit.name;
                $scope.usuariosFiltrados[index].email = $scope.usuarioEdit.email;
                $scope.usuariosFiltrados[index].data_nasc = $scope.usuarioEdit.data_nasc.format("YYYY-MM-DD").toString();
                $scope.usuariosFiltrados[index].funcao = $scope.usuarioEdit.funcao;
                $scope.usuariosFiltrados[index].sexo = $scope.usuarioEdit.sexo;

                $scope.qtdUsuariosInativos();
                $scope.filtrarUsuarios();
                $scope.sortReverseUser = !$scope.sortReverseUser;
                $scope.ordenarUsuarios($scope.sortTypeUser);
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
        if($scope.dataVazioPaciente == undefined) {
            $scope.dataVazioPaciente = true;
        }

        if($scope.nomeVazioPaciente == undefined) {
            $scope.nomeVazioPaciente = true;
        }
        
        if($scope.cpfVazioPaciente == undefined) {
            $scope.cpfVazioPaciente = true;
        }

        if(!$scope.dataVazioPaciente && !$scope.nomeVazioPaciente && !$scope.cpfVazioPaciente) {
            $scope.showSpinnerAddPaciente = true;
            $('#modalAddPaciente').modal('show');

            $scope.novoPaciente.data_nasc = $scope.novoPaciente.data_nasc.format("YYYY-MM-DD").toString();

            $timeout( function() {
                apiService.addPaciente($scope.novoPaciente).then(function(response) {
                    $('#modalAddPaciente').modal('hide');

                    var obj = {};
                    obj.checked = false;
                    obj.nome = $scope.novoPaciente.nome;
                    obj.cpf = $scope.novoPaciente.cpf;
                    obj.data_nasc = $scope.novoPaciente.data_nasc.format("YYYY-MM-DD").toString();
                    obj.email = $scope.novoPaciente.email;

                    $scope.pacientes.push(obj);
                    $scope.filtrarPacientes();
                    $scope.sortReversePaciente = !$scope.sortReversePaciente;
                    $scope.ordenarPacientes($scope.sortTypePaciente);

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
        $scope.pacientesFiltrados = $filter('filterIgnoringAccents')($scope.pacientes, this.searchPaciente);
        $scope.atualizarPagerPacientes(1);
    }

    $scope.ordenarPacientes = function (sortType) {
        $scope.sortReversePaciente = !$scope.sortReversePaciente;
        $scope.sortTypePaciente = sortType;
        $scope.pacientesFiltrados = $filter('localeOrderBy')($scope.pacientesFiltrados, sortType, $scope.sortReversePaciente);
    }

    $scope.excluirPacientes = function() {
        $scope.showSpinnerExcluirPacientes = true;
        $('#modalErroExcluirPacientes').modal('show');

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

        $timeout( function() {
            apiService.excluirPacientes(objCpfs).then(function(response) {
                $('#modalErroExcluirPacientes').modal('hide');

                $scope.checkboxSelecionarPacientes = false;
                
                cpfs.forEach(function(cpf) {
                    var index = _.findIndex($scope.pacientes, function(o) { return o.cpf == cpf; });
                    $scope.pacientes.splice(index, 1);
                });

                $scope.filtrarPacientes();
                $scope.sortReversePaciente = !$scope.sortReversePaciente;
                $scope.ordenarPacientes($scope.sortTypePaciente);
            })
            .catch(function(response) {
                $scope.showSpinnerExcluirPacientes = false;
            })
        }, 1000 );
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

    $scope.checkDataPaciente = function(option) {
        if(option == 'add') {
            if($scope.novoPaciente.data_nasc == '') {
                $scope.dataVazioPaciente = true;
            }
            else {
                $scope.dataVazioPaciente = false;
            }
        }
        else if (option == 'edit') {
            if($scope.pacienteEdit.data_nasc == '') {
                $scope.dataVazioPaciente = true;
            }
            else {
                $scope.dataVazioPaciente = false;
            }
        }
    }

    $scope.checkNomePaciente = function(option) {
        if(option == 'add') {
            if($scope.novoPaciente.nome == '') {
                $scope.nomeVazioPaciente = true;
            }
            else {
                $scope.nomeVazioPaciente = false;
            }
        }
        else if (option == 'edit') {
            if($scope.pacienteEdit.nome == '') {
                $scope.nomeVazioPaciente = true;
            }
            else {
                $scope.nomeVazioPaciente = false;
            }
        }
    }

    $scope.checkCpfPaciente = function(option) {
        if(option == 'add') {
            if($scope.novoPaciente.cpf == '' || $scope.novoPaciente.cpf == undefined) {
                $scope.cpfVazioPaciente = true;
            }
            else {
                $scope.cpfVazioPaciente = false;
            }
        }
        else if (option == 'edit') {
            if($scope.pacienteEdit.cpf == '' || $scope.pacienteEdit.cpf == undefined) {
                $scope.cpfVazioPaciente = true;
            }
            else {
                $scope.cpfVazioPaciente = false;
            }
        }
    }

    $scope.checkCpfExistenciaPaciente = function(option) {
        if(option == 'add') {
            var index = _.findIndex($scope.pacientesFiltrados, function(o) { return o.cpf == $scope.novoPaciente.cpf; });

            if(index == -1) {
                $scope.cpfExistePaciente = false;
            }
            else {
                $scope.cpfExistePaciente = true;
            }
        }
        else if (option == 'edit') {
            var index = _.findIndex($scope.pacientesFiltrados, function(o) { return o.cpf == $scope.pacienteEdit.cpf; });

            if(index == -1) {
                $scope.cpfExistePaciente = false;
            }
            else {
                $scope.cpfExistePaciente = true;
            }
        }
    }

    $scope.setPacienteEdit = function(paciente) {
        $scope.showSpinnerLoadPacienteEdit = true;
        $scope.successLoadPacienteEdit = false;
        $scope.togglePaginas('editPaciente');

        $timeout( function() {
            apiService.getPacienteEdit(paciente.cpf).then(function(response) {
               $scope.pacienteEdit = angular.copy(response.data[0]);
               $scope.showSpinnerLoadPacienteEdit = false;
               $scope.successLoadPacienteEdit = true;
            })
            .catch(function(response) {
                $scope.showSpinnerLoadPacienteEdit = false;
                $('#modalErroLoadPacienteEdit').modal('show');
            })
        }, 1000 );
    }

    $scope.editarPaciente = function(option) {
        $scope.showSpinnerEditPaciente = true;
        $('#modalEditPaciente').modal('show');

        $scope.pacienteEdit.data_nasc = $scope.pacienteEdit.data_nasc.format("YYYY-MM-DD").toString();
        delete $scope.pacienteEdit.created_at;
        delete $scope.pacienteEdit.updated_at;

        $timeout( function() {
            apiService.editarPaciente($scope.pacienteEdit).then(function(response) {
                $('#modalEditPaciente').modal('hide');

                var index = _.findIndex($scope.pacientesFiltrados, function(o) { return o.cpf == $scope.pacienteEdit.cpf; });
                
                $scope.pacientesFiltrados[index].nome = $scope.pacienteEdit.nome;
                $scope.pacientesFiltrados[index].email = $scope.pacienteEdit.email;
                $scope.pacientesFiltrados[index].data_nasc = $scope.pacienteEdit.data_nasc.format("YYYY-MM-DD").toString();

                if(!option) {
                    $scope.togglePaginas('pacientes');
                    $scope.filtrarPacientes();
                    $scope.sortReversePaciente = !$scope.sortReversePaciente;
                    $scope.ordenarPacientes($scope.sortTypePaciente);
                }
            })
            .catch(function(response) {
                $scope.showSpinnerEditPaciente = false;
            })
        }, 1000 );
    }

    $scope.showSpinnerAtendimento = true;

    $scope.setViewPaciente = function(paciente) {
        $scope.showSpinnerGetAtendimento = true;
        $scope.togglePaginas('viewPaciente');
        $scope.viewPaciente = angular.copy(paciente);
        var offsetAtend = -1;

        $timeout( function() {
            apiService.getAtendimentos($scope.viewPaciente.cpf, offsetAtend).then(function(response) {
                $scope.showSpinnerGetAtendimento = false;
                $scope.atendimentos = response.data.atendimentos;
                $scope.qtdAtendimentos = response.data.quantidade;
                $scope.atendimentosNums = response.data.atendimentosNums;
                $scope.atendOffset = $scope.atendimentosNums[0];
            })
            .catch(function(response) {
                $scope.showSpinnerGetAtendimento = false;
                $('#modalErroCarregarAtendimentos').modal('show');
            })
        }, 1000 );
    }

    $scope.calcIdade = function(data) {
        var years = moment().diff(moment(data), 'years'); // full years
        var months = moment().diff(moment(data), 'months');
        var days = moment().diff(moment(data), 'days');

        return years + " anos, " + months + " meses, " + days + " dias";
    }

    $scope.showIniciarAtendimento = true;
    $scope.showFinalizarAtendimento = false;

    $scope.viewResumo = true;

    $scope.toggleAtendimento = function(secao) {
        if(secao == "resumo") {
            $scope.viewResumo = true;
            $scope.viewAtendimento = false;
            $scope.viewMedidas = false;
            $scope.viewDiagProg = false;
        }
        else if(secao == "atendimento") {
            $scope.viewResumo = false;
            $scope.viewAtendimento = true;
            $scope.viewMedidas = false;
            $scope.viewDiagProg = false;
        }
        else if(secao == "medidas") {
            $scope.viewResumo = false;
            $scope.viewAtendimento = false;
            $scope.viewMedidas = true;
            $scope.viewDiagProg = false;
        }
        else if(secao == "diagprog") {
            $scope.viewResumo = false;
            $scope.viewAtendimento = false;
            $scope.viewMedidas = false;
            $scope.viewDiagProg = true;
        }
    }

    $scope.toggleButtonAtendimento = function() {
        if($scope.showFinalizarAtendimento) {
            $scope.toggleAtendimento('resumo');
        }

        $scope.showIniciarAtendimento = !$scope.showIniciarAtendimento;
        $scope.showFinalizarAtendimento = !$scope.showFinalizarAtendimento;
    }

    $scope.dpAtendimentoOptions = {
        format: 'DD-MM-YYYY',
        maxDate: moment(),
        widgetPositioning: {vertical: 'top', horizontal: 'auto'},
        ignoreReadonly: true,
        useCurrent: false,
        showClear: true
    }

    var atendimentoVazio = function(obj) {
        var result = true

        _.forOwn(obj, function(value, key) {
            if(!_.isEmpty(value)) {
                result = false;
            }
        });

        return result;
    }

    $scope.resetAtendimento = function() {
        $scope.atendimento = {};
        $scope.medidas = {};
        $scope.plano_frontal = {};
        $scope.plano_horizontal = {};
        $scope.plano_sagital = {};
        $scope.mobilidade_articular = {};
        $scope.diag_prog = {};
        $scope.curva = {};
        $scope.local_escoliose = {};
        $scope.vertebra = {};
    }

    $scope.resetAtendimento();

    $scope.addAtendimento = function() {
        $scope.showSpinnerAddAtendimento = true;
        $('#modalErroAddAtendimento').modal('show');

        var dados = {};

        if($scope.atendimento.menarca != undefined) {
            $scope.atendimento.menarca = $scope.atendimento.menarca.format("YYYY-MM-DD").toString();
        }

        if($scope.atendimento.data_atendimento != undefined) {
            $scope.atendimento.data_atendimento = $scope.atendimento.data_atendimento.format("YYYY-MM-DD").toString();
        }
        
        if($scope.atendimento.data_raio_x != undefined) {
            $scope.atendimento.data_raio_x = $scope.atendimento.data_raio_x.format("YYYY-MM-DD").toString();
        }
        
        dados.atendimento = $scope.atendimento;
        dados.medidas = $scope.medidas;
        dados.plano_frontal = $scope.plano_frontal;
        dados.plano_horizontal = $scope.plano_horizontal;
        dados.plano_sagital = $scope.plano_sagital;
        dados.mobilidade_articular = $scope.mobilidade_articular;
        dados.diag_prog = $scope.diag_prog;
        dados.curva = $scope.curva;
        dados.local_escoliose = $scope.local_escoliose;
        dados.vertebra = $scope.vertebra;

        $timeout( function() {
            if(!atendimentoVazio(dados)) {
                apiService.addAtendimento($scope.viewPaciente.cpf, dados).then(function(response) {
                    $('#modalErroAddAtendimento').modal('hide');
                    $scope.resetAtendimento();
                    $scope.toggleButtonAtendimento();

                    apiService.getAtendimentos($scope.viewPaciente.cpf, -1).then(function(response) {
                        $scope.atendimentos = response.data.atendimentos;
                        $scope.qtdAtendimentos = response.data.quantidade;
                        $scope.atendimentosNums = response.data.atendimentosNums;
                        $scope.atendOffset = $scope.atendimentosNums[0];
                        this.atendOffset = $scope.atendimentosNums[0];
                    })
                })
                .catch(function(response) {
                    $scope.showSpinnerAddAtendimento = false;
                })
            }
            else {
                $('#modalErroAddAtendimento').modal('hide');
                $scope.toggleButtonAtendimento();
            }
        }, 1000 );
    }

    $scope.cancelarAtendimento = function() {
        $scope.resetAtendimento();
        $scope.toggleButtonAtendimento();
    }

    $scope.atendimentoKeys = [
        ['idade_cronologica', 'Idade cronológica'],
        ['idade_ossea', 'Idade óssea'],
        ['menarca', 'Menarca'],
        ['num_atendimento', 'Número de atendimento'],
        ['data_atendimento', 'Data de atendimento'],
        ['altura', 'Altura'],
        ['altura_sentada', 'Altura sentada'],
        ['peso', 'Peso'],
        ['risser', 'Risser'],
        ['data_raio_x', 'Data raio X']
    ];

    $scope.medidasKeys = [
        ['assimetria_ombro', 'Assimetria ombro'],
        ['assimetria_escapulas', 'Assimetria escápulas'],
        ['hemi_torax', 'Hemi-Tórax'], ['cintura', 'Cintura'],                             
        ['teste_fukuda_deslocamento', 'Teste Fukuda deslocamento'],
        ['teste_fukuda_rotacao', 'Teste Fukuda rotação'],
        ['teste_fukuda_desvio', 'Teste Fukuda desvio'],
        ['habilidade_ocular_direito', 'Habilidade ocular direito'],                             
        ['habilidade_ocular_esquerdo', 'Habilidade ocular esquerdo'],
        ['romberg_mono_direito', 'Romberg mono direito'],
        ['romberg_mono_esquerdo', 'Romberg mono esquerdo'], 
        ['romberg_sensibilizado_direito', 'Romberg sensibilizado direito'],                               
        ['romberg_sensibilizado_esquerdo', 'Romberg sensibilizado esquerdo'],
        ['balanco_direito', 'Balanço direito'],
        ['balanco_esquerdo', 'Balanço esquerdo'],
        ['retracao_posterior', 'Retração posterior'],                              
        ['teste_thomas_direito', 'Teste Thomas direito'],
        ['teste_thomas_esquerdo', 'Teste Thomas esquerdo'],
        ['retracao_peitoral_direito', 'Retração peitoral direito'],
        ['retracao_peitoral_esquerdo', 'Retração peitoral esquerdo'],                              
        ['forca_muscular_abs', 'Força muscular ABS'],
        ['forca_ext_tronco', 'Força extensores tronco'],
        ['resistencia_extensores_tronco', 'Resistência extensores tronco']
    ];

    $scope.planoFrontalKeys = [
        ['calco', 'Calço'],
        ['valor', 'Valor']
    ];

    $scope.planoHorizontalKeys = [
        ['calco', 'Calço'],
        ['valor', 'Valor'],
        ['tipo', 'Tipo'],
        ['vertebra', 'Vértebra']
    ];

    $scope.planoSagitalKeys = [
        ['localizacao', 'Localização'],
        ['valor', 'Valor'],
        ['diferenca', 'Diferença']
    ];

    $scope.mobilidadeArticularKeys = [
        ['lado', 'Lado'],
        ['valor', 'Valor'],
        ['inclinacao', 'Inclinação']
    ];

    $scope.diagProgKeys = [
        ['diagnostico_clinico', 'Diagnóstico clínico'],
        ['tipo_escoliose', 'Tipo escoliose'],
        ['cifose', 'Cifose'],
        ['lordose', 'Lordose'],
        ['prescricao_medica', 'Prescrição médica'],
        ['prescricao_fisioterapeutica', 'Prescrição fisioterapêutica'],
        ['colete', 'Colete'],
        ['colete_hs', 'Colete HS'],
        ['etiologia', 'Etiologia'],
        ['idade_aparecimento', 'Idade aparecimento'],
        ['topografia', 'Topografia'],
        ['calco', 'Calço'],
        ['hpp', 'HPP']
    ];

    $scope.vertebraKeys = [
        ['tipo', 'Tipo'],
        ['local', 'Local'],
        ['altura', 'Altura'],
        ['vertebra_nome', 'Nome da vértebra']
    ];

    $scope.localEscolioseKeys = [
        ['local', 'Local'], 
        ['lado', 'Lado']
    ];

    $scope.curvaKeys = [
        ['ordenacao', 'Ordenação'],
        ['tipo', 'Tipo'],
        ['angulo_cobb', 'Ângulo COBB'],
        ['angulo_ferguson', 'Ângulo Ferguson'],
        ['grau_rotacao', 'Grau rotação']
    ];

    $scope.refreshTableAtend = function() {
        $scope.atendOffset = this.atendOffset;

        if($scope.atendOffset > 0 && $scope.atendOffset <= $scope.qtdAtendimentos && $scope.atendOffset != $scope.atendimentosNums[0]) {
            $('#modalErroTabelaAtendimentos').modal('show');
            $scope.showSpinnerTabelaAtendimentos = true;

            $timeout( function() {
                apiService.getAtendimentos($scope.viewPaciente.cpf, $scope.atendOffset).then(function(response) {
                    $('#modalErroTabelaAtendimentos').modal('hide');
                    $scope.atendimentos = response.data.atendimentos;
                    $scope.atendimentosNums = response.data.atendimentosNums;
                })
                .catch(function(response) {
                    $scope.showSpinnerTabelaAtendimentos = false;
                    this.atendOffset = $scope.atendimentosNums[0];
                })
            }, 500 );
        }
        else {
            this.atendOffset = $scope.atendimentosNums[0];
        }
    }

    $scope.showAtendKey = function(nomeTabela) {
        return function(key) {
            var res = false;

            for(var i = 0; i < $scope.atendimentos.length; i++) {
                _.forOwn($scope.atendimentos[i], function(value1, prop1) {
                    if(prop1 == nomeTabela){
                        _.forOwn(value1, function(value2, prop2) {
                            if(key[0] == prop2 && value2 != null){
                                res = true;
                            }
                        });
                    }
                });
            }

            return res;
        }
    }

    $scope.countShowAtendKey = function(array, nomeTabela) {
        var count = 0;

        for(var j = 0; j < array.length; j++) {
            var find = false;

            for(var i = 0; i < $scope.atendimentos.length; i++) {
                _.forOwn($scope.atendimentos[i], function(value1, prop1) {
                    if(prop1 == nomeTabela){
                        _.forOwn(value1, function(value2, prop2) {
                            if(array[j][0] == prop2 && value2 != null){
                                find = true;
                            }
                        });
                    }
                });
            }

            if(find)
                count++;
        }

        return count;
    }
});