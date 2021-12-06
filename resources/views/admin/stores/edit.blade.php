@extends('layouts.app')

@section('content')

    <h1>Atualizar Loja</h1>
    <form action="{{route('admin.stores.update', ['store' => $store->id])}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="name">Nome Loja</label>
            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{$store->name}}" id="name">
            @error('name')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="description">Descrição</label>
            <input class="form-control @error('description') is-invalid @enderror" type="text" name="description" value="{{$store->description}}" id="description">
            @error('description')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="phone">Telefone</label>
            <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone" value="{{$store->phone}}" id="phone">
            @error('phone')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="mobile_phone">Celular</label>
            <input class="form-control @error('mobile_phone') is-invalid @enderror" type="text" name="mobile_phone" value="{{$store->mobile_phone}}" id="mobile_phone">
            @error('mobile_phone')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
        </div>
        <div class="form-group">
            <p><img src="{{asset('storage/'.$store->logo)}}" alt="" class="img-fluid"></p>
            <label for="logo">Logo da loja</label>
            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" id="logo">
        </div>
        @error('logo')
            <div class="invalid-feedback">
                {{$message}}
            </div>
        @enderror
        <div class="form-group">
            <button class="btn btn-success" type="submit">Atualizar Loja</button>
        </div>
    </form>

@endsection

@section('scripts')
    <script>
        let imPhone = new Inputmask('(99) 9999-9999'),
            imMobilePhone = new Inputmask('(99) 99999-9999')

            
        imPhone.mask(document.getElementById('phone'))
        imMobilePhone.mask(document.getElementById('mobile_phone'))
    </script>
@endsection