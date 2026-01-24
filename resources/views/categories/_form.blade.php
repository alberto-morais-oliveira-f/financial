<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('name', 'Nome') }}
            {{ Form::text('name', null, ['class' => 'form-control', 'required']) }}
        </div>
    </div>
</div>

<div class="form-group mt-4">
    {{ Form::submit('Salvar', ['class' => 'btn btn-primary']) }}
    <a href="{{ route('financial.categories.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
