@extends('financial::layouts.master', ['title' => 'Estornar Pagamento'])

@section('content')
<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
            <form action="{{ route('financial.payments.refund.store', $payment->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="alert alert-info">
                        Você está prestes a estornar o pagamento <strong>#{{ $payment->id ?? '' }}</strong>.
                    </div>

                    <div class="form-group mb-4">
                        <label for="amount">Valor a Estornar (em centavos)</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ $payment->amount ?? 0 }}" required>
                        <small class="form-text text-muted">O valor máximo para estorno é {{ $payment->amount ?? 0 }} centavos.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="reason">Motivo do Estorno</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Ex: Cliente solicitou cancelamento"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Confirmar Estorno</button>
                    <a href="{{ route('financial.payments.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
