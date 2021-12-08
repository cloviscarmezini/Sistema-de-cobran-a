@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="my-3 text-right">
            <a href="{{route('accounts.create')}}" class="btn btn-primary">Novo título</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Vencimento</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                        <tr>
                            <td>{{$account->client->name}}</td>
                            <td>{{$account->description}}</td>
                            <td>R${{number_format($account->value, 2, ',', '.')}}</td>
                            <td>{{$account->expiration_date->format('d/m/Y')}}</td>
                            <td> @php echo $account->status_description @endphp</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{route('accounts.view', ['account' => $account->id])}}" class="btn btn-link"><i class="fas fa-eye"></i></a>
                                    <a href="{{route('accounts.edit', ['account' => $account->id])}}" class="btn btn-link"><i class="fas fa-edit"></i></a>
                                    <form id="{{$account->id}}" action="{{route('accounts.destroy', ['account' => $account->id])}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-link text-danger" onClick="deleteConfirm('{{$account->id}}')" type="button"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="6">Nenhum título cadastrado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{$accounts->links()}}
        </div>
    </div>
@endsection
