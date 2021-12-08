@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Importação de Títulos</h2>
        <div class="row">
            <div class="col">
                <form action="{{ route('accounts.import.upload') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mt-4">
                        <input type="file" name="file" class="form-control" accept=".csv">
                    </div>
                    <button class="w-100 btn btn-lg btn-primary mt-4" type="submit">Importar</button>
                </form>
            </div>
        </div>
    </div>

@endsection
