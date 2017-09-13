'use strict';
var app = angular.module('peb', ['angularSpinner', '720kb.tooltips', 'ae-datetimepicker', 'ngAnimate', 'ui.bootstrap', 'thatisuday.ng-image-gallery', 'ngImageCompress'], function($interpolateProvider, $qProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
    $qProvider.errorOnUnhandledRejections(false);
});

app.config(['ngImageGalleryOptsProvider', function(ngImageGalleryOptsProvider){
    ngImageGalleryOptsProvider.setOpts({
        thumbSize: 150
    });
}]);

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
                  
                    if (newInputLength > 0 && dotCount > 1)
                        newInput = newInput.slice(0, newInputLength - 1);

                    if ((newInput.slice(newInput.indexOf(".") + 1).length) > 4)
                        newInput = newInput.slice(0, newInputLength - 1);
                    
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
        if(input == "" || input == undefined || input == null)
            return "---";
        else
            return input;
    }
});

app.filter('dateBr', function() {
    return function(input) {
        if(input != undefined && input != null)
            return input.toString().split('-')[2] + '-' + input.toString().split('-')[1] + '-' + input.toString().split('-')[0];
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

        if (input == undefined)
            return array;
        else if (input.toString() == "")
            return array;
        else {
            for(var i = 0; i < array.length; i++) {
                _.forOwn(array[i], function(value, key) {
                    if(value != null && value != undefined && value != "") {
                        var clearedValue = resolverAcentuacao(value.toString().toLowerCase());
                        var clearedInput = resolverAcentuacao(input.toLowerCase());

                        if(clearedValue.indexOf(clearedInput) != -1)
                            arrayFiltrado.push(array[i]);
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

        checkExistenciaCpfPaciente: function(cpf) {
            return $http.get('/usuario/checkExistenciaCpfPaciente/' + cpf);
        },

        checkExistenciaCpfResponsavel: function(cpf) {
            return $http.get('/usuario/checkExistenciaCpfResponsavel/' + cpf);
        },

        excluirPacientes: function(ids) {
            return $http.post('/usuario/excluirPacientes', ids);
        },

        getPacienteEdit: function(id) {
            return $http.get('/usuario/getPacienteEdit/' + id);
        },

        editarPaciente: function(paciente) {
            return $http.put('/usuario/editarPaciente', paciente);
        },

        addAtendimento: function(id, dados) {
            return $http.post('/usuario/addAtendimento/' + id, dados);
        },

        getIdadeAparecimento: function(id) {
            return $http.get('/usuario/getIdadeAparecimento/' + id);
        },

        getAtendimentos: function(id, offset) {
            return $http.get('/usuario/getAtendimentos/' + id + '/' + offset);
        },

        listarFotos: function(nome, cpf, num, cpfUsuario) {
            return $http.get('/usuario/listarFotos/' + nome + '/' + cpf + '/' + num + '/' + cpfUsuario);
        },

        getQtdFotosAtend: function(nome, cpf, num) {
            return $http.get('/usuario/getQtdFotosAtend/' + nome + '/' + cpf + '/' + num);
        },

        deletarFotos: function(imgs) {
            return $http.post('/usuario/deletarFotos', imgs);
        }
	}
});
 
app.controller('pebController', function($scope, apiService, $filter, $timeout, $http) {
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
        }, 500 );       
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
            if($scope.usuariosFiltrados[i].checked)
                cpfs.push($scope.usuariosFiltrados[i].cpf);
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
        }, 500 );    
    }

	$scope.sortTypeUser = 'funcao';
	$scope.sortReverseUser = true;
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

            $scope.pacienteMenorIdade = false;
            $scope.novoResponsavel = {};
            $scope.nomeVazioResponsavel = undefined;
            $scope.cpfVazioResponsavel = undefined;
            $scope.cpfExisteResponsavel = false;
            $scope.cpfExistePaciente = false;
        }
        else if (pagina == 'editPaciente') {
            $scope.showUsuarios = false;
            $scope.showPacientes = false;
            $scope.showAddPacientes = false;
            $scope.showEditPacientes = true;
            $scope.showViewPacientes = false;

            $scope.dataVazioPaciente = undefined;
            $scope.nomeVazioPaciente = undefined;

            $scope.pacienteMenorIdade = false;
            $scope.nomeVazioResponsavel = undefined;
            $scope.cpfVazioResponsavel = undefined;
            $scope.cpfExisteResponsavel = false;
            $scope.cpfExistePaciente = false;
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
                if($scope.usuariosFiltrados[i].cpf != $scope.cpfLogged())
                    $scope.usuariosFiltrados[i].checked = true;
            }
        }
        else {
            for(var i = 0; i < $scope.usuariosFiltrados.length; i++)
                $scope.usuariosFiltrados[i].checked = false;
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

        if ($scope.pagerObjectUsuarios.currentPage == $scope.pagerObjectUsuarios.totalPages)
            $scope.pageSizeUsuarios = $scope.usuariosFiltrados.length - $scope.pageSizeUsuarios*($scope.pagerObjectUsuarios.totalPages-1);
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
        $scope.usuarioEditCopy = angular.copy(usuario);
    }

    $scope.salvarEdicaoUsuario = function () {
        $scope.showSpinnerEditarUsuario = true;
        $('#modalErroEditarUsuario').modal('show');

        $scope.usuarioEdit.data_nasc = $scope.usuarioEdit.data_nasc.format("YYYY-MM-DD").toString();

        var diffUsuario = _.omitBy($scope.usuarioEdit, function(v, k) {
            return $scope.usuarioEditCopy[k] === v;
        });

        diffUsuario.cpf = $scope.usuarioEdit.cpf;

        if($scope.cpfLogged() == $scope.usuarioEdit.cpf)
            $scope.usuarioLogado = $scope.usuarioEdit.name.split(" ")[0];

        apiService.editarUsuario(diffUsuario).then(function(res) {
            $timeout( function() {
                if(res.data.usuario != undefined && res.data.usuario == null) {
                    $('#modalErroEditarUsuario').modal('hide');

                    $scope.usuariosFiltrados = _.remove($scope.usuariosFiltrados, function(u) {
                        return u.cpf != $scope.usuarioEdit.cpf;
                    });
                }
                else {
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
                }
            }, 500 );
        })
        .catch(function(res) {
            $timeout( function() {
                $scope.showSpinnerEditarUsuario = false;
            }, 500 );
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
        if($scope.usuarioEdit.email == '')
            $scope.emailVazioEditarUsuario = true;
        else
            $scope.emailVazioEditarUsuario = false;
    }

    $scope.checkNomeEditarUsuario = function() {
        if($scope.usuarioEdit.name == '')
            $scope.nomeVazioEditarUsuario = true;
        else
            $scope.nomeVazioEditarUsuario = false;
    }

    $scope.checkEmailExistenciaEditarUsuario = function() {
        var index = _.findIndex($scope.usuariosFiltrados, function(o) { return o.email == $scope.usuarioEdit.email; });

        if(index == -1)
            $scope.emailExisteEditarUsuario = false;
        else
            $scope.emailExisteEditarUsuario = true;
    }

    $scope.dpNovoPacienteOptions = {
        format: 'DD-MM-YYYY',
        maxDate: moment(),
        widgetPositioning: {vertical: 'bottom', horizontal: 'auto'},
        ignoreReadonly: true,
        useCurrent: false
    }

    $scope.addPaciente = function(option) {
        if($scope.dataVazioPaciente == undefined)
            $scope.dataVazioPaciente = true;

        if($scope.nomeVazioPaciente == undefined)
            $scope.nomeVazioPaciente = true;

        if($scope.pacienteMenorIdade && $scope.cpfVazioResponsavel == undefined)
            $scope.cpfVazioResponsavel = true;

        if($scope.pacienteMenorIdade && $scope.nomeVazioResponsavel == undefined && !$scope.cpfExisteResponsavel)
            $scope.nomeVazioResponsavel = true;

        if(!$scope.dataVazioPaciente && !$scope.nomeVazioPaciente && !$scope.nomeVazioResponsavel && !$scope.cpfVazioResponsavel) {
            $scope.showSpinnerAddPaciente = true;
            $('#modalAddPaciente').modal('show');

            $scope.novoPaciente.data_nasc = $scope.novoPaciente.data_nasc.format("YYYY-MM-DD").toString();

            var obj = {};
            obj.paciente = angular.copy($scope.novoPaciente);

            if($scope.pacienteMenorIdade)
                obj.responsavel = angular.copy($scope.novoResponsavel);

            $timeout( function() {
                apiService.addPaciente(obj).then(function(response) {
                    $('#modalAddPaciente').modal('hide');

                    var obj = {};
                    obj.checked = false;
                    obj.nome = $scope.novoPaciente.nome;
                    obj.cpf = $scope.novoPaciente.cpf;
                    obj.id = response.data;
                    obj.data_nasc = $scope.novoPaciente.data_nasc.format("YYYY-MM-DD").toString();
                    obj.email = $scope.novoPaciente.email;

                    $scope.pacientes.push(obj);
                    $scope.filtrarPacientes();
                    $scope.sortReversePaciente = !$scope.sortReversePaciente;
                    $scope.ordenarPacientes($scope.sortTypePaciente);

                    if(option)
                        $scope.novoPaciente = {};
                    else
                        $scope.togglePaginas('pacientes');
                })
                .catch(function(response) {
                    $scope.showSpinnerAddPaciente = false;
                })
            }, 500 );
        }        
    }

    $scope.sortTypePaciente = 'nome';
    $scope.sortReversePaciente = true;
    $scope.searchPaciente = '';

    $scope.atualizarPagerPacientes = function(page) {
        $scope.pageSizePacientes = defaultPageSize;
        
        $scope.pagerObjectPacientes = $scope.pager($scope.pacientesFiltrados, page, $scope.pageSizePacientes);

        if ($scope.pagerObjectPacientes.currentPage == $scope.pagerObjectPacientes.totalPages)
            $scope.pageSizePacientes = $scope.pacientesFiltrados.length - $scope.pageSizePacientes*($scope.pagerObjectPacientes.totalPages-1);
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

        var ids = [];

        for(var i = 0; i < $scope.pacientesFiltrados.length; i++) {
            if($scope.pacientesFiltrados[i].checked)
                ids.push($scope.pacientesFiltrados[i].id);
        }

        var objIds = ids.reduce(function(acc, cur, i) {
            acc[i] = cur;
            return acc;
        }, {});

        $timeout( function() {
            apiService.excluirPacientes(objIds).then(function(response) {
                $('#modalErroExcluirPacientes').modal('hide');

                $scope.checkboxSelecionarPacientes = false;
                
                ids.forEach(function(id) {
                    var index = _.findIndex($scope.pacientes, function(o) { return o.id == id; });
                    $scope.pacientes.splice(index, 1);
                });

                $scope.filtrarPacientes();
                $scope.sortReversePaciente = !$scope.sortReversePaciente;
                $scope.ordenarPacientes($scope.sortTypePaciente);
            })
            .catch(function(response) {
                $scope.showSpinnerExcluirPacientes = false;
            })
        }, 500 );
    }

    $scope.checkboxSelecionarPacientes = false;

    $scope.selecionarTodosPacientes = function() {
        $scope.checkboxSelecionarPacientes = !$scope.checkboxSelecionarPacientes;

        if($scope.checkboxSelecionarPacientes) {
            for(var i = 0; i < $scope.pacientesFiltrados.length; i++)
                $scope.pacientesFiltrados[i].checked = true;
        }
        else {
            for(var i = 0; i < $scope.pacientesFiltrados.length; i++)
                $scope.pacientesFiltrados[i].checked = false;
        }
    }

    $scope.checkMaioridadePaciente = function(option) {
        if(option == 'add') {
            if(moment().diff(moment($scope.novoPaciente.data_nasc), 'years') < 18)
                $scope.pacienteMenorIdade = true;
            else
                $scope.pacienteMenorIdade = false;
        }
        else if (option == 'edit') {
            if(moment().diff(moment($scope.pacienteEdit.data_nasc), 'years') < 18)
                $scope.pacienteMenorIdade = true;
            else
                $scope.pacienteMenorIdade = false;
        }

        $scope.novoResponsavel = {};
        $scope.nomeVazioResponsavel = undefined;
        $scope.cpfVazioResponsavel = undefined;
        $scope.cpfExisteResponsavel = false;
    }

    $scope.checkDataPaciente = function(option) {
        if(option == 'add') {
            if($scope.novoPaciente.data_nasc == '')
                $scope.dataVazioPaciente = true;
            else
                $scope.dataVazioPaciente = false;
        }
        else if (option == 'edit') {
            if($scope.pacienteEdit.data_nasc == '')
                $scope.dataVazioPaciente = true;
            else
                $scope.dataVazioPaciente = false;
        }
    }

    $scope.checkNomeResponsavel = function(option) {
        if(option == 'add') {
            if($scope.pacienteMenorIdade) {
                if($scope.novoResponsavel.nome == '')
                    $scope.nomeVazioResponsavel = true;
                else
                    $scope.nomeVazioResponsavel = false;
            }
            else
                $scope.nomeVazioResponsavel = false;
        }
        else if (option == 'edit') {
            if($scope.pacienteMenorIdade) {
                if($scope.responsavelEdit.nome == '')
                    $scope.nomeVazioResponsavel = true;
                else
                    $scope.nomeVazioResponsavel = false;
            }
            else
                $scope.nomeVazioResponsavel = false;
        }
    }

    $scope.checkCpfResponsavel = function(option) {
        if($scope.novoResponsavel.cpf == '' || $scope.novoResponsavel.cpf == undefined) {
            $scope.cpfVazioResponsavel = true;
            $scope.cpfExisteResponsavel = false;
        }
        else
            $scope.cpfVazioResponsavel = false;
    }

    $scope.checkCpfExistenciaResponsavel = function() {
        if($scope.novoResponsavel.cpf != '' && $scope.novoResponsavel.cpf != undefined) {
            apiService.checkExistenciaCpfResponsavel($scope.novoResponsavel.cpf).then(function(response) {
                if(response.data == 0)
                    $scope.cpfExisteResponsavel = false;
                else {
                    $scope.cpfExisteResponsavel = true;
                    $scope.novoResponsavel = angular.copy(response.data);
                }
            })
        }
    }

    $scope.checkNomePaciente = function(option) {
        if(option == 'add') {
            if($scope.novoPaciente.nome == '')
                $scope.nomeVazioPaciente = true;
            else
                $scope.nomeVazioPaciente = false;
        }
        else if(option == 'edit') {
            if($scope.pacienteEdit.nome == '')
                $scope.nomeVazioPaciente = true;
            else
                $scope.nomeVazioPaciente = false;
        } 
    }

    $scope.checkCpfExistenciaPaciente = function(option) {
        if(option == 'add') {
            if($scope.novoPaciente.cpf != '' && $scope.novoPaciente.cpf != undefined) {
                apiService.checkExistenciaCpfPaciente($scope.novoPaciente.cpf).then(function(response) {
                    if(response.data == 1)
                        $scope.cpfExistePaciente = true;
                    else
                        $scope.cpfExistePaciente = false;
                })
            }
        }
        else if(option == 'edit') {
            if($scope.pacienteEdit.cpf != '' && $scope.pacienteEdit.cpf != undefined) {
                apiService.checkExistenciaCpfPaciente($scope.pacienteEdit.cpf).then(function(response) {
                    if(response.data == 1)
                        $scope.cpfExistePaciente = true;
                    else
                        $scope.cpfExistePaciente = false;
                })
            }
        }
    }

    $scope.setPacienteEdit = function(paciente) {
        $scope.showSpinnerLoadPacienteEdit = true;
        $scope.successLoadPacienteEdit = false;
        $scope.togglePaginas('editPaciente');

        $timeout( function() {
            apiService.getPacienteEdit(paciente.id).then(function(response) {
                if(response.data.paciente == null) {
                    $scope.showSpinnerLoadPacienteEdit = false;
                    $scope.successLoadPacienteEdit = true;
                    $scope.togglePaginas('pacientes');

                    $scope.pacientesFiltrados = _.remove($scope.pacientesFiltrados, function(p) {
                        return p.id != paciente.id;
                    });
                }
                else {
                    $scope.pacienteEdit = angular.copy(response.data.paciente);
                    $scope.pacienteEditCopy = angular.copy(response.data.paciente);
                    $scope.showSpinnerLoadPacienteEdit = false;
                    $scope.successLoadPacienteEdit = true;

                    if(response.data.hasOwnProperty('responsavel')) {
                        $scope.responsavelEdit = angular.copy(response.data.responsavel);
                        $scope.responsavelEditCopy = angular.copy(response.data.responsavel);
                        $scope.pacienteMenorIdade = true;
                    }
                    else {
                        $scope.responsavelEdit = {};
                        $scope.responsavelEditCopy = {};
                        $scope.pacienteMenorIdade = false;
                    }   
                }            
            })
            .catch(function(response) {
                $scope.showSpinnerLoadPacienteEdit = false;
                $('#modalErroLoadPacienteEdit').modal('show');
            })
        }, 500 );
    }

    $scope.editarPaciente = function(option) {
        $scope.showSpinnerEditPaciente = true;
        $('#modalEditPaciente').modal('show');

        $scope.pacienteEdit.data_nasc = $scope.pacienteEdit.data_nasc.format("YYYY-MM-DD").toString();

        var diffPaciente = _.omitBy($scope.pacienteEdit, function(v, k) {
            return $scope.pacienteEditCopy[k] === v;
        });

        var diffResponsavel = _.omitBy($scope.responsavelEdit, function(v, k) {
            return $scope.responsavelEditCopy[k] === v;
        });

        diffPaciente.id = $scope.pacienteEdit.id;

        var obj = {};
        obj.paciente = diffPaciente;
        obj.responsavel = diffResponsavel;

        $timeout( function() {
            apiService.editarPaciente(obj).then(function(response) {
                if(response.data.paciente != undefined && response.data.paciente == null) {
                    $('#modalEditPaciente').modal('hide');
                    $scope.togglePaginas('pacientes');

                    $scope.pacientesFiltrados = _.remove($scope.pacientesFiltrados, function(p) {
                        return p.id != obj.paciente.id;
                    });
                }
                else {
                    $('#modalEditPaciente').modal('hide');

                    var index = _.findIndex($scope.pacientesFiltrados, function(o) { return o.id == $scope.pacienteEdit.id; });
                    
                    $scope.pacientesFiltrados[index].nome = $scope.pacienteEdit.nome;
                    $scope.pacientesFiltrados[index].cpf = $scope.pacienteEdit.cpf;
                    $scope.pacientesFiltrados[index].email = $scope.pacienteEdit.email;
                    $scope.pacientesFiltrados[index].data_nasc = $scope.pacienteEdit.data_nasc.format("YYYY-MM-DD").toString();

                    if(!option) {
                        $scope.togglePaginas('pacientes');
                        $scope.filtrarPacientes();
                        $scope.sortReversePaciente = !$scope.sortReversePaciente;
                        $scope.ordenarPacientes($scope.sortTypePaciente);
                    }
                    else {
                        $scope.pacienteEditCopy = angular.copy($scope.pacienteEdit);
                        $scope.responsavelEditCopy = angular.copy($scope.responsavelEdit);
                    }
                }
            })
            .catch(function(response) {
                $scope.showSpinnerEditPaciente = false;
            })
        }, 500 );
    }

    $scope.showSpinnerAtendimento = true;

    $scope.setViewPaciente = function(paciente) {
        $scope.showSpinnerGetAtendimento = true;
        $scope.togglePaginas('viewPaciente');
        $scope.viewPaciente = angular.copy(paciente);
        var offsetAtend = -1;

        $timeout( function() {
            apiService.getAtendimentos($scope.viewPaciente.id, offsetAtend).then(function(response) {
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
        }, 500 );
    }

    $scope.calcIdade = function(data) {
        var years = moment().diff(moment(data), 'years');
        var months = moment().diff(moment(data).add(years, 'y'), 'months');
        var days = moment().diff(moment(data).add(years, 'y').add(months, 'M'), 'days');

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
        if($scope.showFinalizarAtendimento)
            $scope.toggleAtendimento('resumo');

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
            if(!_.isEmpty(value))
                result = false;
        });

        return result;
    }

    $scope.resetAtendimento = function() {
        $scope.atendimento = {};
        $scope.medidas = {};
        $scope.plano_frontal = {};
        $scope.plano_horizontal_milimetros = {};
        $scope.plano_horizontal_graus = {};
        $scope.plano_sagital = {};
        $scope.mobilidade_articular = {};
        $scope.diag_prog = {};
        $scope.curva = {};
        $scope.curva1 = {};
        $scope.curva2 = {};
        $scope.curva3 = {};
        $scope.curva4 = {};
        $scope.vertebra_apice = {};
        $scope.vertebra_limite = {};
    }

    $scope.resetAtendimento();

    $scope.addAtendimento = function() {
        $scope.showSpinnerAddAtendimento = true;
        $('#modalErroAddAtendimento').modal('show');

        var dados = {};

        if($scope.atendimento.menarca != undefined)
            $scope.atendimento.menarca = $scope.atendimento.menarca.format("YYYY-MM-DD").toString();

        if($scope.atendimento.data_atendimento != undefined)
            $scope.atendimento.data_atendimento = $scope.atendimento.data_atendimento.format("YYYY-MM-DD").toString();
        
        if($scope.atendimento.data_raio_x != undefined)
            $scope.atendimento.data_raio_x = $scope.atendimento.data_raio_x.format("YYYY-MM-DD").toString();
        
        dados.atendimento = $scope.atendimento;
        dados.medidas = $scope.medidas;
        dados.plano_frontal = $scope.plano_frontal;
        dados.plano_horizontal_milimetros = $scope.plano_horizontal_milimetros;
        dados.plano_horizontal_graus = $scope.plano_horizontal_graus;
        dados.plano_sagital = $scope.plano_sagital;
        dados.mobilidade_articular = $scope.mobilidade_articular;
        
        if($scope.diag_prog.idade_aparecimento == "")
            $scope.diag_prog.idade_aparecimento = undefined;

        dados.diag_prog = $scope.diag_prog;
        
        $scope.curva.curva1 = $scope.curva1;
        $scope.curva.curva2 = $scope.curva2;
        $scope.curva.curva3 = $scope.curva3;
        $scope.curva.curva4 = $scope.curva4;

        dados.curva = $scope.curva;
        dados.vertebra_apice = $scope.vertebra_apice;
        dados.vertebra_limite = $scope.vertebra_limite;

        $timeout( function() {
            if(!atendimentoVazio(dados)) {
                apiService.addAtendimento($scope.viewPaciente.id, dados).then(function(response) {
                    $('#modalErroAddAtendimento').modal('hide');
                    $scope.resetAtendimento();
                    $scope.toggleButtonAtendimento();

                    apiService.getAtendimentos($scope.viewPaciente.id, -1).then(function(response) {
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
        }, 500 );
    }

    $scope.cancelarAtendimento = function() {
        $scope.resetAtendimento();
        $scope.toggleButtonAtendimento();
    }

    $scope.atendimentoKeys = [
        ['data', 'Data'],
        ['altura', 'Altura'],
        ['altura_sentada', 'Altura sentada'],
        ['peso', 'Peso'],
        ['data_raio_x', 'Data raio X'],
        ['risser', 'Risser']
    ];

    $scope.medidasOneKeys = [
        ['assimetria_ombro', 'Assimetria ombro'],
        ['assimetria_escapulas', 'Assimetria escápulas'],
        ['hemi_torax', 'Hemi-Tórax'],
        ['cintura', 'Cintura'],                             
    ];

    $scope.medidasTwoKeys = [                        
        ['teste_fukuda_deslocamento_direito', 'Teste Fukuda deslocamento direito'],
        ['teste_fukuda_deslocamento_esquerdo', 'Teste Fukuda deslocamento esquerdo'],
        ['teste_fukuda_rotacao_direito', 'Teste Fukuda rotação direito'],
        ['teste_fukuda_rotacao_esquerdo', 'Teste Fukuda rotação esquerdo'],
        ['teste_fukuda_desvio_direito', 'Teste Fukuda desvio direito'],
        ['teste_fukuda_desvio_esquerdo', 'Teste Fukuda desvio esquerdo'],
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
        ['valor', 'Valor'],
        ['calco_utilizado', 'Calço utilizado'],
        ['tamanho_calco', 'Tamanho do calço']
    ];

    $scope.planoHorizontalMilimetrosKeys = [
        ['calco_utilizado', 'Calço utilizado'],
        ['valor', 'Valor'],
        ['tipo', 'Tipo'],
        ['vertebra', 'Vértebra']
    ];

    $scope.planoHorizontalGrausKeys = [
        ['calco_utilizado', 'Calço utilizado'],
        ['valor', 'Valor'],
        ['tipo', 'Tipo'],
        ['vertebra', 'Vértebra']
    ];

    $scope.planoSagitalKeys = [
        ['valor_cabeca', 'Cabeça'],
        ['compensacao_cabeca', 'Compensação cabeça'],
        ['valor_cervical', 'Cervical'],
        ['compensacao_cervical', 'Compensação cervical'],
        ['valor_c7', 'C7'],
        ['compensacao_c7', 'Compensação C7'],
        ['valor_t5_t6', 'T5-T6'],
        ['compensacao_t5_t6', 'Compensação T5-T6'],
        ['valor_t12', 'T12'],
        ['compensacao_t12', 'Compensação T12'],
        ['valor_l3', 'L3'],
        ['compensação_l3', 'Compensação L3'],
        ['valor_s1', 'S1'],        
        ['compensacao_s1', 'Compensação S1']
    ];

    $scope.mobilidadeArticularKeys = [
        ['valor_reto_direita', 'Reto direita'],
        ['valor_inclinado_direita', 'Inclinado direita'],
        ['diferenca_direita', 'Diferença direita'],
        ['valor_reto_esquerda', 'Reto esquerda'],
        ['valor_inclinado_esquerda', 'Inclinado esquerda'],
        ['diferenca_esquerda', 'Diferença esquerda']
    ];

    $scope.refreshTableAtend = function() {
        $scope.atendOffset = this.atendOffset;

        if($scope.atendOffset > 0 && $scope.atendOffset <= $scope.qtdAtendimentos && $scope.atendOffset != $scope.atendimentosNums[0]) {
            $('#modalErroTabelaAtendimentos').modal('show');
            $scope.showSpinnerTabelaAtendimentos = true;

            $timeout( function() {
                apiService.getAtendimentos($scope.viewPaciente.id, $scope.atendOffset).then(function(response) {
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
        else
            this.atendOffset = $scope.atendimentosNums[0];
    }

    $scope.showAtendKey = function(nomeTabela) {
        return function(key) {
            var res = false;

            for(var i = 0; i < $scope.atendimentos.length; i++) {
                _.forOwn($scope.atendimentos[i], function(value1, prop1) {
                    if(prop1 == nomeTabela){
                        _.forOwn(value1, function(value2, prop2) {
                            if(key[0] == prop2 && value2 != null)
                                res = true;
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
                            if(array[j][0] == prop2 && value2 != null)
                                find = true;
                        });
                    }
                });
            }

            if(find)
                count++;
        }

        return count;
    }

    $scope.showAtendimento = false;
    $scope.tabAtendimento = -1;
    $scope.dataHoraAtendimento = {};

    $scope.toggleViewAtendimento = function(index) {
        if($scope.tabAtendimento == index){
            $scope.showAtendimento = !$scope.showAtendimento;
            $scope.tabAtendimento = -1;
        }
        else {
            $scope.tabAtendimento = index;
            $scope.atendimentoFull = $scope.atendimentos[index];
            $scope.showAtendimento = true;

            var split = $scope.atendimentoFull.atendimento.data_hora.split(" ");
            var hora = split[1];
            $scope.dataHoraAtendimento.hora = hora.split(":")[0] + ":" + hora.split(":")[1];

            var data = split[0];
            $scope.dataHoraAtendimento.dia = data.split("-")[0];
            $scope.dataHoraAtendimento.mes = data.split("-")[1];
            $scope.dataHoraAtendimento.ano = data.split("-")[2];
            $scope.dataHoraAtendimento.mesExtenso = moment.monthsShort('-MMM-', $scope.dataHoraAtendimento.mes - 1);

            $scope.getQtdFotosAtend();
        }
    }

    $scope.getQtdFotosAtend = function() {
        apiService.getQtdFotosAtend($scope.viewPaciente.nome, $scope.viewPaciente.cpf, $scope.atendimentosNums[$scope.tabAtendimento]).then(function(response) {
            $scope.qtdFotosAtend = response.data;
        })
    }

    $scope.setDadosPaciente = function() {
        $scope.showSpinnerDadosPacientes = true;
        $scope.erroDadosPaciente = undefined;

        $timeout( function() {
            apiService.getPacienteEdit($scope.viewPaciente.id).then(function(response) {
                $scope.showSpinnerDadosPacientes = false;
                $scope.erroDadosPaciente = false;
                $scope.dadosPaciente = response.data;

                var dataSplit = $scope.dadosPaciente.paciente.data_nasc.split("-");
                $scope.dadosPaciente.paciente.data_nasc = dataSplit[2] + "-" + dataSplit[1] + "-" + dataSplit[0];
            })
            .catch(function(response) {
                $scope.erroDadosPaciente = true;
                $scope.showSpinnerDadosPacientes = false;
            })
        }, 500 );
    }

    function dataURLtoFile(dataurl, filename) {
        var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1], bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        
        while(n--)
            u8arr[n] = bstr.charCodeAt(n);
        
        return new File([u8arr], filename, {type:mime});
    }

    var fotosData = new FormData();

    $scope.uploadFotos = function (nome, cpf, num) {
        if($scope.imageList.length > 0) {
            var id = 0;

            angular.forEach($scope.imageList, function (value, key) {
                fotosData.append('image_file_' + id, dataURLtoFile(value.compressed.dataURL, value.file.name));
                id = id + 1;
            });

            var request = {
                method: 'POST',
                url: '/usuario/uploadFotos/' + nome + '/' + cpf + '/' + num,
                data: fotosData,
                headers: {
                    'Content-Type': undefined
                }
            };

            $http(request).then(
                function success(e) {
                    angular.element('#image_file').val(null);
                    fotosData = new FormData();
                    $scope.imagesAtend = [];
                    $scope.imageList = [];
                    $scope.listarFotos(nome, cpf, num, $scope.cpfLogged());
                    $scope.getQtdFotosAtend();
                    $scope.erroListarFotos = false;
                }, function error(e) {
                    angular.element('#image_file').val(null);
                    fotosData = new FormData();
                    $scope.imageList = [];
                }
            );
        }
    };

    $scope.imageList = [];

    $scope.listarFotos = function (nome, cpf, num) {
        $scope.showSpinnerFotos = true;
        $scope.erroListarFotos = false;

        $timeout( function() {
            apiService.listarFotos(nome, cpf, num, $scope.cpfLogged()).then(function(response) {
                $scope.showSpinnerFotos = false;

                if(response.data.funcao != "Analista") {
                    for(var i = 0; i < response.data.fotos.length; i++) {
                        $scope.imagesAtend.push({
                            id: i,
                            url: response.data.fotos[i],
                            deletable: true
                        });
                    }
                }
                else {
                    for(var i = 0; i < response.data.fotos.length; i++) {
                        $scope.imagesAtend.push({
                            id: i,
                            url: response.data.fotos[i]
                        });
                    }
                }
            })
            .catch(function(response) {
                $scope.showSpinnerFotos = false;
                $scope.erroListarFotos = true;
            })
        }, 500 );
    }

    $('#modalFotoAtendimento').on('hidden.bs.modal', function (e) {
        $scope.imagesAtend = [];
        $scope.imageList = [];
    })

    $scope.deleteImage = function(img, cb) {
        cb();

        var image = [];
        image.push(img);

        apiService.deletarFotos(image).then(function(response){
            $scope.getQtdFotosAtend();
        })
    }

    $scope.deletarFotos = function() {
        apiService.deletarFotos($scope.imagesAtend).then(function(response){
            $scope.imagesAtend = [];
            $scope.getQtdFotosAtend();
        })
    }

    $scope.showTamanhoCalco = function(option) {
        if(option == "frontal") {
            if($scope.plano_frontal.calco_utilizado != undefined && $scope.plano_frontal.calco_utilizado == "Sim")
                return true;
            else
                return false;
        }
        else if(option == "diag_prog_direito") {
            if($scope.diag_prog.calco_utilizado_direito != undefined && $scope.diag_prog.calco_utilizado_direito == "Sim")
                return true;
            else
                return false;
        }
        else if(option == "diag_prog_esquerdo") {
            if($scope.diag_prog.calco_utilizado_esquerdo != undefined && $scope.diag_prog.calco_utilizado_esquerdo == "Sim")
                return true;
            else
                return false;
        }
    }

    var removerLastZeros = function(number) {
        var num = number.toString();

        for (var i = num.length - 1; i > 0; i--) {
            if(num[i] == "0")
                num = num.slice(0, i);
            else if(num[i] == ".") {
                num = num.slice(0, i);
                break;
            }
            else
                break;
        }

        num = num.slice(0, 6);

        return num;
    }

    $scope.diferencaMobiArt = function(lado) {
        if(lado == "direita") {
            if($scope.mobilidade_articular.valor_reto_direita != undefined && $scope.mobilidade_articular.valor_reto_direita != ""
               && $scope.mobilidade_articular.valor_inclinado_direita != undefined && $scope.mobilidade_articular.valor_inclinado_direita != "") {
                $scope.mobilidade_articular.diferenca_direita = removerLastZeros(Math.abs($scope.mobilidade_articular.valor_reto_direita - $scope.mobilidade_articular.valor_inclinado_direita).toFixed(4));
            }
        }
        else if(lado == "esquerda") {
            if($scope.mobilidade_articular.valor_reto_esquerda != undefined && $scope.mobilidade_articular.valor_reto_esquerda != ""
               && $scope.mobilidade_articular.valor_inclinado_esquerda != undefined && $scope.mobilidade_articular.valor_inclinado_esquerda != "") {
                $scope.mobilidade_articular.diferenca_esquerda = removerLastZeros(Math.abs($scope.mobilidade_articular.valor_reto_esquerda - $scope.mobilidade_articular.valor_inclinado_esquerda).toFixed(4));
            }
        }
    }

    $scope.getIdadeAparecimento = function() {
        apiService.getIdadeAparecimento($scope.viewPaciente.id).then(function(response) {
            $scope.diag_prog.idade_aparecimento = response.data;
        })
    }

    $scope.iconSideBar = 1;

    $scope.showSideBar = function() {
        var ic_sidebar = angular.element( document.querySelector( '.ic-sidebar' ) );
        var sidebar_records = angular.element( document.querySelector( '.sidebar-records' ) );
        var left = ic_sidebar.css('left');
        var opacity = sidebar_records.css('opacity');

        if(opacity == "0")
            sidebar_records.css('opacity', '1');
        else
            sidebar_records.css('opacity', '0');

        if(left == "-280px")
            ic_sidebar.css('left', '0');
        else
            ic_sidebar.css('left', '-280px');
        
        $scope.iconSideBar = opacity;
    }
});