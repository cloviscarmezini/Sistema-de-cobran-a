@extends('layouts.app')

@section('content')

    <h1>Criar Categoria</h1>
    <form action="{{route('admin.categories.store')}}" method="post">
        @csrf
        <div class="form-group">
            <label for="name">Nome</label>
            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{old('name')}}" id="name">
            @error('name')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="description">Descrição</label>
            <input class="form-control @error('description') is-invalid @enderror" type="text" name="description" value="{{old('description')}}" id="description">
            @error('description')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Criar categoria</button>
        </div>
    </form>

@endsection