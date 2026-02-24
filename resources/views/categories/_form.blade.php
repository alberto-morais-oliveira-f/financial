<div class="row">
    <div class="col-md-6">
        {!! Form::text('name', 'Nome')->required() !!}
    </div>
    <div class="col-md-6">
        {!! Form::select('type', 'Tipo', ['expense' => 'Despesa', 'income' => 'Receita'], null, ['class' =>
        'form-control', 'required']) !!}
    </div>
</div>

<div class="form-group mt-4">
    {!! Form::submit('Salvar')->primary() !!}
    <a href="{{ route('financial.categories.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
