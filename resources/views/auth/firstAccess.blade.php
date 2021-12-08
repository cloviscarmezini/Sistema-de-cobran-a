@extends('layouts.login')

@section('content')
<div class="p-5">
    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-4">Primeiro acesso</h1>
    </div>
    <form method="POST" action="{{ route('client.firstAccess') }}">
        @csrf
        <div class="form-group">
            <input id="email"
                type="email"
                class="form-control form-control-user @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
                placeholder="E-mail"
            >
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input
                id="password"
                type="password"
                class="form-control form-control-user @error('password') is-invalid @enderror"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Senha"
            >

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input
                id="password_confirmation"
                type="password"
                class="form-control form-control-user @error('password_confirmation') is-invalid @enderror"
                name="password_confirmation"
                required
                placeholder="Confirmar senha"
            >

            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-user btn-block">
            Registrar-se
        </button>
    </form>
    <hr>
    <div class="text-center">
        <a class="small" href="/">Voltar</a>
    </div>
</div>


@endsection
