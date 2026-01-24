@extends('layouts.master', ['title' => 'Categorias'])

@section('btn_create')
    <a href="{{ route('financial.categories.create') }}" class="btn btn-success btn-sm float-end">
        Nova Categoria
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
