@extends('layouts.master', ['title' => 'Editar Fornecedor'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('financial.suppliers.update', $supplier->uuid) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('financial::suppliers._form', ['supplier' => $supplier])
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                            <a href="{{ route('financial.suppliers.index') }}" class="btn btn-secondary">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
