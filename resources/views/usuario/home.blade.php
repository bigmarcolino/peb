@extends('usuario.layout.auth')

@section('content')

    @if (Auth::user()->funcao == 'examinador')
        @include('usuario.layout.examinador')

    @elseif (Auth::user()->funcao == 'analista')
        @include('usuario.layout.analista')

    @elseif (Auth::user()->funcao == 'admin')
        @include('usuario.layout.admin')

    @endif

@endsection
