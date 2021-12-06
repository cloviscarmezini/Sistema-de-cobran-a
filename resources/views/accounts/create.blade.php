@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Novo vencimento</h1>
        <form action="{{route('accounts.store')}}" method="post">
            @csrf
            <div class="form-group">
                <label for="client_id">Cliente</label>
                <select class="form-control @error('client_id') is-invalid @enderror" name="client_id" id="client_id" required>
                    <option value="" disabled selected>Selecione um cliente</option>
                    @foreach($clients as $client)
                        <option value="{{$client->id}}" {{old('client_id') === $client->id ? 'selected' : ''}}>{{$client->name}}</option>
                    @endforeach
                </select>
                @error('client_id')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Descrição</label>
                <input class="form-control @error('description') is-invalid @enderror" type="text" value="{{old('description')}}" name="description" id="description" required>
                @error('description')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                @enderror
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="type">Tipo</label>
                        <select class="form-control @error('type') is-invalid @enderror" name="type" id="type" required>
                            <option value="" disabled selected>Selecione o tipo</option>
                            <option value="Boleto" {{old('type') === 'Boleto' ? 'selected' : ''}}>Boleto</option>
                            <option value="Nota Promissória" {{old('type') === 'Nota Promissória' ? 'selected' : ''}}>Nota Promissória</option>
                            <option value="Cartão de crédito" {{old('type') === 'Cartão de crédito' ? 'selected' : ''}}>Cartão de crédito</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="readjustment_type">Tipo de reajuste</label>
                        <select class="form-control @error('readjustment_type') is-invalid @enderror" name="readjustment_type" id="readjustment_type" required>
                            <option value="" disabled selected>Selecione o tipo de reajuste</option>
                            <option value="INPC" {{old('readjustment_type') === 'INPC' ? 'selected' : ''}}>INPC</option>
                            <option value="IPCA" {{old('readjustment_type') === 'IPCA' ? 'selected' : ''}}>IPCA</option>
                            <option value="Dolar" {{old('readjustment_type') === 'Dolar' ? 'selected' : ''}}>Dolar</option>
                        </select>
                        @error('readjustment_type')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="value">Valor</label>
                        <input class="form-control @error('value') is-invalid @enderror" type="text" value="{{old('value')}}" name="value" id="value" required>
                        @error('value')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="discount">% de desconto para pagamento à vista</label>
                        <input class="form-control @error('discount') is-invalid @enderror" type="text" value="{{old('discount')}}" name="discount" id="discount" required>
                        @error('discount')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="installments">Parcelar em: (max)</label>
                        <select class="form-control @error('installments') is-invalid @enderror" name="installments" id="installments" required>
                            @foreach(range(1, 12) as $installment)
                                <option value="{{$installment}}" {{old('installments') === $installment ? 'selected' : ''}}>{{"{$installment}x"}}</option>
                            @endforeach
                        </select>
                        @error('installments')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="expiration_date">Data de vencimento</label>
                        <input class="form-control @error('expiration_date') is-invalid @enderror" type="date" expiration_date="{{old('expiration_date')}}" name="expiration_date" id="expiration_date" required>
                        @error('expiration_date')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Salvar</button>
            </div>
        </form>
    </div>
@endsection
