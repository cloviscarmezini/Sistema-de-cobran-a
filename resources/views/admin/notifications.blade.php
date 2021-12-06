@extends('layouts.app')

@section('content')
    @if(auth()->user()->store)
        <div class="row">
            <div class="col-12">
                <a href="{{route('admin.notifications.read.all')}}" class="btn btn-success">Marcar todas como lidas</a>
                <hr>
            </div>
        </div>
    
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Notificação</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unreadNotifications as $n)
                        <tr>
                            <td>{{$n->data['message']}}</td>
                            <td>{{$n->created_at->locale('pt')->diffForHumans()}}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{route('admin.notifications.read', ['notification' => $n->id])}}" class="btn btn-sm btn-primary">Marcar como lida</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="alert alert-warning">
                                    Você não possui nenhuma notificaçao!
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

@endsection