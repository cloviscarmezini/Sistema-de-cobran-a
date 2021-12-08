@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Detalhes do título</h2>
        <div class="row mt-5">
            <div class="col">
                <div class="form-group">
                    <label class="font-weight-bold" for="description">Descrição</label>
                    <input class="form-control border-0 bg-light" value="{{$account->description}}" readonly>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="type">Tipo</label>
                    <input class="form-control border-0 bg-light" type="text" value="{{$account->type}}" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label class="font-weight-bold">Valor</label>
                    <input class="form-control border-0 bg-light" value="R${{ number_format($account->value, 2, ',', '.') }}" readonly>
                </div>
            </div>
            @if($account->tradeInstallments->count() == 1)
                <div class="col">
                    <div class="form-group">
                        <label class="font-weight-bold">Desconto</label>
                        <input class="form-control border-0 bg-light" value="{{$account->discount}}%" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label class="font-weight-bold" for="value">Valor final</label>
                        <input class="form-control border-0 bg-light" value="R${{number_format(($account->value - ($account->value / 100 * $account->discount)), 2, ',', '.')}}" readonly>
                    </div>
                </div>
            @endif
        </div>
        @csrf
        <div class="row">
            <div class="col">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>Parcela</td>
                            <td>Valor</td>
                            <td>Data de vencimento</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($account->tradeInstallments as $tradeInstallment)
                            <tr>
                                <td>{{$tradeInstallment->installment}}</td>
                                <td>
                                    @if($tradeInstallment->installment == 1)
                                        @if($account->tradeInstallments->count() == 1)
                                            R${{number_format(($tradeInstallment->value - ($account->value / 100 * $account->discount)), 2, ',', '.')}}
                                        @else
                                            R${{ number_format($tradeInstallment->value, 2, ',', '.') }}
                                        @endif
                                    @else
                                        R${{number_format($tradeInstallment->value, 2, ',', '.')}}
                                    @endif
                                </td>
                                <td>{{$tradeInstallment->expiration_date->format('d/m/Y')}}</td>
                                <td>
                                    @if($tradeInstallment->status)
                                        <button class="btn btn-success">Pago</button>
                                    @elseif($tradeInstallment->expiration_date < date('Y-m-d'))
                                        <button class="btn btn-danger">Vencido</button>
                                    @else
                                        <button type="button" class="btn btn-warning">Aguardando pagamento</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="4">Cliente não realizou a negociação</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
