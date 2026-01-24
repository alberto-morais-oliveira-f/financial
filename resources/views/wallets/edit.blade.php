@extends('layouts.master', ['title' => 'Editar Carteira'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Editar Carteira</h4>
                </div>
                <div class="card-body">
                    {{ Form::model($wallet, ['route' => ['financial.wallets.update', $wallet->id], 'method' => 'PUT']) }}
                        @include('financial::wallets._form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
