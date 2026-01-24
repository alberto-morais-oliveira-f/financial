@extends('layouts.master', ['title' => 'Editar Categoria'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Editar Categoria</h4>
                </div>
                <div class="card-body">
                    {{ Form::model($category, ['route' => ['financial.categories.update', $category->id], 'method' => 'PUT']) }}
                        @include('financial::categories._form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
