@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="my-3 text-right">
            <a href="{{route('clients.create')}}" class="btn btn-primary">Adicionar cliente</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Cidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>{{$client->name}}</td>
                            <td>{{$client->document}}</td>
                            <td>{{$client->email}}</td>
                            <td>{{$client->phone}}</td>
                            <td>{{"{$client->district} - {$client->state}"}}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{route('clients.edit', ['client' => $client->id])}}" class="btn btn-link"><i class="fas fa-edit"></i></a>
                                    <form id="{{$client->id}}" action="{{route('clients.destroy', ['client' => $client->id])}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-link text-danger" onClick="deleteConfirm('{{$client->id}}')" type="button"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="6">Nenhum cliente cadastrado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{$clients->links()}}
        </div>
    </div>
@endsection
