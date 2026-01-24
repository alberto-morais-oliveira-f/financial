@extends('financial::layouts.master', ['title' => 'Nova Transferência'])

@section('content')
<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
            <form action="{{ route('financial.transactions.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group mb-4">
                        <label for="from_wallet_id">Carteira de Origem</label>
                        <select class="form-control" id="from_wallet_id" name="from_wallet_id" required>
                            {{-- Loop com as carteiras disponíveis --}}
                            <option value="">Selecione a origem</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="to_wallet_id">Carteira de Destino</label>
                        <select class="form-control" id="to_wallet_id" name="to_wallet_id" required>
                            {{-- Loop com as carteiras disponíveis --}}
                            <option value="">Selecione o destino</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="amount">Valor (em centavos)</label>
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Ex: 5000 para R$ 50,00" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="description">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Realizar Transferência</button>
                    <a href="{{ route('financial.transactions.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
