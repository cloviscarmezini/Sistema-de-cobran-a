@extends('layouts.app')

@section('content')

    <h1>Atualizar Categoria</h1>
    <form action="{{route('admin.categories.update', ['category' =>$category->id])}}" method="post">
        @csrf
        @method('put')
        <div class="form-group">
            <label for="name">Nome</label>
            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{$category->name}}" id="name">
            @error('name')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="description">Descrição</label>
            <input class="form-control @error('description') is-invalid @enderror" type="text" name="description" value="{{$category->description}}" id="description">
            @error('description')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Atualizar categoria</button>
        </div>
    </form>

@endsection