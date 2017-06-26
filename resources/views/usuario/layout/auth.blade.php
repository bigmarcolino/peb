<!DOCTYPE html>

<html lang="{{ config('app.locale') }}" ng-app="peb">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="/img/favicon.png"/>
        <meta name="theme-color" content="#1a6f9d"/>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel Multi Auth Guard') }}</title>

        <!-- Styles -->
        <link href="/css/commons.min.css" rel="stylesheet" type="text/css">
        <link href="/css/main.min.css" rel="stylesheet" type="text/css">
        <link href="/css/base.min.css" rel="stylesheet" type="text/css">
        <link href="/css/app.css" rel="stylesheet" type="text/css">
        <link href="/bower_components/jasny-bootstrap/dist/css/jasny-bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">
        <link href="/css/peb.css" rel="stylesheet" type="text/css">

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>

        <script src="/node_modules/jquery/dist/jquery.min.js" type="text/javascript"></script>
        <script src="/node_modules/angular/angular.min.js" type="text/javascript"></script>
        <script src="/js/peb.js" type="text/javascript"></script>
    </head>

    <body ng-controller="pebController">
        @if (Auth::check())
            <div class="clearfix ic-header">
                
                <div class="ic-header-default">
                    <div class="text-center logo">
                        <a href="{{ url('/usuario/home') }}">
                            <img src="/img/logo-white.png" alt="Projeto Escoliose Brasil" class="logo-white">
                        </a>
                    </div>
                    
                    <ul class="pull-left nav nav-pills main-nav" role="tablist">
                        <li role="presentation" ng-click="togglePaginas('pacientes')" ng-class="{'open': showPacientes}">
                            <a href="">Pacientes</a>
                        </li>

                        @if (Auth::user()->funcao == "admin")
                            <li role="presentation" ng-click="togglePaginas('usuarios')" ng-class="{'open': showUsers}">
                                <a href="#">
                                    Usuários
                                    <span class="badge">[[ countUsuariosInativos ]]</span>
                                </a>
                            </li>
                        @endif
    
                        <!--
                        <li class="dropdown nav-more-dropdown">
                            <a href="#" class="dropdown-toggle" role="button" aria-expanded="false">
                                Mais <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href=""><span class="icon"><span class="s-capsule"></span></span> Bulas</a></li>
                                <li><a href=""><span class="icon"><span class="s-phone"></span></span> Contatos</a></li>
                                <li><a href=""><span class="icon"><span class="s-book"></span></span> CID 10</a></li>
                                <li><a href=""><span class="icon"><span class="s-folder"></span></span> Logs</a></li>
                                <li><a href=""><span class="icon"><span class="s-wheel"></span></span> TISS</a></li>
                            </ul>
                        </li> -->
                    </ul>

                    <ul class="list-table pull-right no-selection nav-tools">
                        <li class="li-funcao">
                            @if (Auth::user()->funcao == "admin")
                                <img src="/img/admin-logo.png" alt="Admin">
                            @elseif (Auth::user()->funcao == "examinador")
                                <img src="/img/examinador-logo.png" alt="Examinador">
                            @elseif (Auth::user()->funcao == "analista")
                                <img src="/img/analista-logo.png" alt="Analista">
                            @endif
                        </li>

                        <li class="dropdown account-dropdown">
                            <span class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="ib account-image empty">
                                    <span class="name-abbr">
                                        {{ explode(" ", Auth::user()->name)[0] }}
                                    </span>
                                    <span id="logged" style="display: none">
                                        {{ Auth::user()->cpf }}
                                    </span>                                     
                                </span>
                                
                                <span class="m-l-es s-arrow-dow-white account-arrow"></span>
                            </span>

                            <ul class="dropdown-menu" role="menu">                                
                                <li>
                                    <a href="{{ url('/usuario/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span class="fa fa-sign-out" aria-hidden="true"></span>
                                        Sair
                                    </a>

                                    <form id="logout-form" action="{{ url('/usuario/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div> <!-- /.ic-mobile-default -->

                <div class="ic-header-mobile">
                    <ul class="items-header">
                        <li>
                            <button class="ic-open-menu" data-toggle="offcanvas" data-target="#myNavmenu" data-canvas="body">
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>
                        </li>

                        <li>
                            <img src="/img/logo-white.png" alt="Projeto Escoliose Brasil" class="logo-white">
                        </li>

                        <li></li>                        
                    </ul>
                </div> <!-- /.ic-header-mobile -->
            </div> <!-- /.ic-header -->

            <div class="ic-menu-mobile offcanvas" id="myNavmenu" role="navigation">
                <div class="menu-customer">
                    <div class="name-customer ib">
                        <p>{{ explode(" ", Auth::user()->name)[0] }}</p>
                    </div>
                </div> <!-- /.menu-customer -->

                <nav class="nav-mobile">
                    <ul>
                        <li ng-click="togglePaginas('pacientes')"><a href="">Pacientes</a></li>

                        @if (Auth::user()->funcao == "admin")
                            <li ng-click="togglePaginas('usuarios')"><a href="">Usuários</a></li>
                        @endif

                        <li>
                            <a href="{{ url('/usuario/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout">
                                <span class="fa fa-sign-out" aria-hidden="true"></span>
                                Sair
                            </a>

                            <form id="logout-form" action="{{ url('/usuario/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </nav> <!-- /.nav-mobile -->
            </div> <!-- /.ic-menu-mobile -->
        @endif

        @yield('content')

        <!-- Scripts -->
        <script src="/js/app.js" type="text/javascript"></script>
        <script src="/bower_components/jasny-bootstrap/dist/js/jasny-bootstrap.min.js" type="text/javascript"></script>
        <script src="/node_modules/moment/min/moment.min.js" type="text/javascript"></script>
        <script src="/node_modules/moment/locale/pt-br.js" type="text/javascript"></script>
        <script src="/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            $("#myNavmenu").offcanvas({ toggle: false, disableScrolling: false, canvas: "body" })

            $(window).on('resize', function(){
                if($(this).width() > 1023){
                    $('#myNavmenu').offcanvas('hide');
                }
            });
        </script>
    </body>
</html>
