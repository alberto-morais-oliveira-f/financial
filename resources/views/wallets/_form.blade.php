<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('name', 'Nome') }}
            {{ Form::text('name', null, ['class' => 'form-control', 'required']) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('type', 'Tipo') }}
            {{ Form::select('type', \Am2tec\Financial\Domain\Enums\WalletType::asSelectArray(), null, ['class' => 'form-control', 'required']) }}
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('balance', 'Saldo Inicial') }}
            {{ Form::number('balance', null, ['class' => 'form-control', 'required']) }}
        </div>
    </div>
</div>

<div class="form-group mt-4">
    {{ Form::submit('Salvar', ['class' => 'btn btn-primary']) }}
    <a href="{{ route('financial.wallets.index') }}" class="btn btn-secondary">Cancelar</a>
</div>
