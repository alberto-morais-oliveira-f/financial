@extends('layouts.master', ['title' => 'Carteiras'])

@section('btn_create')
    <a href="{{ route('financial.wallets.create') }}" class="btn btn-success btn-sm float-end">
        Nova Carteira
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body datatables-table">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endsection
