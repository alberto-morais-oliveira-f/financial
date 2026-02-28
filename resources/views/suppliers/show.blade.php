@extends('layouts.master', ['title' => 'Detalhes do Fornecedor'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Nome:</label>
                            <p>{{ $supplier->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">E-mail:</label>
                            <p>{{ $supplier->email ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Documento (CPF/CNPJ):</label>
                            <p>{{ $supplier->document ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Telefone:</label>
                            <p>{{ $supplier->phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold">Endereço:</label>
                            <p>{{ $supplier->address ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Status:</label>
                            <p>
                                <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $supplier->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('financial.suppliers.edit', $supplier->uuid) }}" class="btn btn-primary">Editar</a>
                        <a href="{{ route('financial.suppliers.index') }}" class="btn btn-secondary">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
