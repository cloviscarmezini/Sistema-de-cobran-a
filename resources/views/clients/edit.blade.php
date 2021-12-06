@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Atualizar cliente</h1>
        <form action="{{route('clients.update', ['client' => $client->id])}}" method="post">
            @csrf
            @method('put')
            <div class="form-group">
                <label for="name">Nome</label>
                <input class="form-control @error('name') is-invalid @enderror" type="text" value="{{$client->name}}" name="name" id="name" required>
                @error('name')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input class="form-control @error('email') is-invalid @enderror" type="text" value="{{$client->email}}" name="email" id="email" required>
                @error('email')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="document">CPF</label>
                <input class="form-control @error('document') is-invalid @enderror" type="text" value="{{$client->document}}" name="document" id="document" required>
                @error('document')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="phone">Telefone</label>
                <input class="form-control @error('phone') is-invalid @enderror" type="text" value="{{$client->phone}}" name="phone" id="phone" required>
                @error('phone')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="zip_code">CEP</label>
                        <input class="form-control @error('zip_code') is-invalid @enderror" type="number" value="{{$client->zip_code}}" name="zip_code" id="zip_code" required>
                        @error('zip_code')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="address">Rua</label>
                        <input class="form-control @error('address') is-invalid @enderror" type="text" value="{{$client->address}}" name="address" id="address" readonly required>
                        @error('address')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="number">Número</label>
                        <input class="form-control @error('number') is-invalid @enderror" type="text" value="{{$client->number}}" name="number" id="number" required>
                        @error('number')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="district">Cidade</label>
                        <input class="form-control @error('district') is-invalid @enderror" type="text" value="{{$client->district}}" name="district" id="district" readonly required>
                        @error('district')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="state">Estado</label>
                        <input class="form-control @error('state') is-invalid @enderror" type="text" value="{{$client->state}}" name="state" id="state" readonly required>
                        @error('state')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Atualizar</button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script defer>
        $('#zip_code').on('keyup', function(e) {
            const zipCode = (e.target.value).replace(/[^0-9]/g,'');

            if(zipCode.length === 8) {
                $.getJSON(`https://viacep.com.br/ws/${zipCode}/json`, function(response) {
                    $('#address').val(response.logradouro);
                    $('#district').val(response.localidade);
                    $('#state').val(response.uf);
                    $('#number').focus()
                })
            }
        })

        //"viacep.com.br/ws/01001000/json/"
    </script>
@endsection
