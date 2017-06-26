@extends('usuario.layout.auth')

@section('content')
<section class="centered-container">
    <div class="centered-inner">
        <div class="row">
            <div class="col col-sm-6 col-sm-offset-3 centered-form">
                <div>
                    <img src="/img/logo.png" alt="Projeto Escoliose Brasil" class="centered-logo">
                </div>

                <h1 class="text-large">Cadastre-se</h1>

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/usuario/register') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Nome:" autofocus>

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email:">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('cpf') ? ' has-error' : '' }}">
                        <input id="cpf" type="text" class="form-control" name="cpf" value="{{ old('cpf') }}" placeholder="CPF:" ng-model="registerCpf" numbers-only>

                        @if ($errors->has('cpf'))
                            <span class="help-block">
                                <strong>{{ $errors->first('cpf') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('sexo') ? ' has-error' : '' }}">
                        <select class="form-control" id="sexo" name="sexo" value="{{ old('sexo') }}" ng-model="selectSexo" ng-change="changeDefaultSelectColor()" ng-class="{'sexo-default-color': defaultSelectColor}">
                            <option hidden value="">Sexo:</option>
                            <option>Masculino</option>
                            <option>Feminino</option>
                        </select>

                        @if ($errors->has('sexo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('sexo') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('data_nasc') ? ' has-error' : '' }}">
                        <input id="data_nasc" type="text" class="form-control" name="data_nasc" value="{{ old('data_nasc') }}" placeholder="Data de Nascimento:" readonly>

                        @if ($errors->has('data_nasc'))
                            <span class="help-block">
                                <strong>{{ $errors->first('data_nasc') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input id="password" type="password" class="form-control" name="password" placeholder="Senha:">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirme a senha:">

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group no-margin-bottom">
                        <button type="submit" class="btn btn-block btn-green semi-bold">
                            CADASTRAR
                        </button>

                        <a class="link-gray btn-block" href="{{ url('/usuario/login') }}">
                            Voltar
                        </a>
                    </div>

                    @if (Session::has('registrado'))
                        <div class="alert alert-success"> {{ Session::get('registrado') }} </div>
                    @endif

                    <script>
                        $('div.alert').delay(3000).slideUp(300);

                        $(function () {
                            $('#data_nasc').datetimepicker({
                                format: 'DD-MM-YYYY',
                                maxDate: moment().subtract(18, 'years'),
                                widgetPositioning: {vertical: 'top', horizontal: 'auto'},
                                ignoreReadonly: true,
                                useCurrent: false
                            }).on('dp.show', function() {
                                return $(this).data('DateTimePicker').defaultDate(moment().subtract(18, 'years').format("DD-MM-YYYY"));
                            });
                        });
                    </script>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
