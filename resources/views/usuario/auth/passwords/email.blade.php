@extends('usuario.layout.auth')

<!-- Main Content -->
@section('content')
<section class="centered-container">
    <div class="centered-inner">
        <div class="row">
            <div class="col col-sm-6 col-sm-offset-3 centered-form">
                <div>
                    <img src="/img/logo.png" alt="Projeto Escoliose" class="centered-logo">
                </div>

                <h1 class="text-large">Será enviado um link de redefinição de senha para seu email</h1>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/usuario/password/email') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email:" autofocus>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group no-margin-bottom">
                        <button type="submit" class="btn btn-block btn-green semi-bold">
                            <span class="fa fa-envelope" aria-hidden="true"></span>
                            ENVIAR
                        </button>

                        <a class="link-gray btn-block" href="{{ url('/usuario/login') }}">
                            Voltar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
