@extends('layouts.app')

@section('content')
    @if(!$store)
        <a href="{{route('admin.stores.create')}}" class="btn btn-success">Criar loja</a>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Loja</th>
                        <th>Total de produtos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$store->id}}</td>
                        <td>{{$store->name}}</td>
                        <td>{{$store->products->count()}}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{route('admin.stores.edit', ['store' => $store->id])}}" class="btn btn-sm btn-primary">Editar</a>
                                <form action="{{route('admin.stores.destroy', ['store' => $store->id])}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-sm btn-danger" type="submit">Remover</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

@endsection