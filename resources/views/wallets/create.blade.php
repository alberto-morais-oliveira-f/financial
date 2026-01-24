@extends('layouts.master', ['title' => 'Nova Carteira'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Nova Carteira</h4>
                </div>
                <div class="card-body">
                    {{ Form::model($wallet, ['route' => 'financial.wallets.store', 'method' => 'POST']) }}
                        @include('financial::wallets._form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
