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

                    <script type="text/javascript" src="{!! asset('js/jquery-3.2.1.min.js') !!}"></script>

                    <script>
                        $('div.alert').delay(3000).slideUp(300);
                    </script>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
