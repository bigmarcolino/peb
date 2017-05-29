@extends('usuario.layout.auth')

@section('content')
<section class="centered-container">
    <div class="centered-inner">
        <div class="row">
            <div class="col col-sm-6 col-sm-offset-3 centered-form">
                <div>
                    <img src="/img/logo.png" alt="Projeto Escoliose Brasil" class="centered-logo">
                </div>

                <h1 class="text-large">Login</h1>

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/usuario/login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">                            
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email:" autofocus>

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

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-green semi-bold">
                            <span class="fa fa-lock" aria-hidden="true"></span>
                            ENTRAR
                        </button>

                        <a class="link-gray btn-block" href="{{ url('/usuario/password/reset') }}">
                            Esqueci a senha
                        </a>
                    </div>

                    <div class="form-group button-register-container">
                        <a href="{{ url('/usuario/register') }}" class="btn btn-block btn-primary semi-bold">
                            CADASTRAR
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
