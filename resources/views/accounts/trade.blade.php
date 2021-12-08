@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Negociar título</h2>
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
                    <label class="font-weight-bold" for="value">Valor</label>
                    <input class="form-control border-0 bg-light" value="R${{number_format($account->value, 2, ',', '.')}}" readonly>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label class="font-weight-bold" for="discount">Desconto à vista</label>
                    <input class="form-control border-0 bg-light" type="text" value="{{$account->discount}}%" readonly>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label class="font-weight-bold" for="expiration_date">Data de vencimento da proposta</label>
                    <input class="form-control border-0 bg-light" type="date" value="{{$account->expiration_date->format('Y-m-d')}}" readonly>
                </div>
            </div>
        </div>
        <hr>
        <form action="{{route('accounts.doTrade', ['account_id' => $account->id])}}" method="post">
            @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="installments">Forma de pagamento</label>
                        <select class="form-control @error('installments') is-invalid @enderror" name="installments" id="installments" required>
                            @foreach(range(1, $account->installments) as $installment)
                                <option value="{{$installment}}">
                                    @if($installment == 1)
                                        {{$installment.' x R$'.number_format(($account->value - ($account->value / 100 * $account->discount)), 2, ',', '.')}}
                                    @else
                                        {{$installment.' x R$'.number_format(($account->value / $installment), 2, ',', '.')}}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('installments')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <button class="btn btn-success" type="submit">Negociar</button>
            </div>
        </form>
    </div>

@endsection
