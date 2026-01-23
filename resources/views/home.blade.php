@extends('financial::layouts.master', ['title' => 'Dashboard Financeiro'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Bem-vindo ao Módulo Financeiro
                </div>
                <div class="card-body">
                    <p>Esta é a página inicial do módulo financeiro.</p>
                    <p>Você pode customizar esta view publicando os arquivos:</p>
                    <code>php artisan vendor:publish --tag=financial-views</code>
                </div>
            </div>
        </div>
    </div>
@endsection