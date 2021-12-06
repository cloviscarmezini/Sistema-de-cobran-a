@extends('layouts.login')

@section('content')
<div class="p-5">
    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-4">Registro</h1>
    </div>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <input id="name"
                type="name"
                class="form-control form-control-user @error('name') is-invalid @enderror"
                name="name" value="{{ old('name') }}"
                required
                autocomplete="name"
                autofocus
                placeholder="Nome"
            >
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <select
                class="form-control form-control-user @error('role') is-invalid @enderror"
                name="role"
                required
                placeholder="Cargo"
            >
                <option value="" selected disabled>Selecione o cargo</option>
                <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Gerente</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
            @error('role')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
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
