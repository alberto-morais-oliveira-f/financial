@extends('layouts.master', ['title' => 'Novo Fornecedor'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('financial.suppliers.store') }}" method="POST">
                        @csrf
                        @include('financial::suppliers._form')
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('financial.suppliers.index') }}" class="btn btn-secondary">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
