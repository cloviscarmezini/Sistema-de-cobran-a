@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
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
                            <td>{{ $account->description }}</td>
                            <td>
                                @if($account->tradeInstallments->count() == 1)
                                    R${{number_format(($account->value - ($account->value / 100 * $account->discount)), 2, ',', '.')}}
                                @else
                                    R${{ number_format($account->value, 2, ',', '.') }}
                                @endif
                            </td>
                            <td>{{ $account->expiration_date->format('d/m/Y') }}</td>
                            <td> @php echo $account->status_description @endphp</td>
                            <td>
                                <div class="btn-group">
                                    @if($account->status === 1 || $account->status === 2)
                                        <a href="{{route('accounts.installments', ['account_id' => $account->id])}}" class="btn btn-primary">Visualizar</a>
                                    @elseif($account->status === 4)
                                        <a href="{{route('accounts.trade', ['account_id' => $account->id])}}" class="btn btn-success">Negociar</i></a>
                                    @endif
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
