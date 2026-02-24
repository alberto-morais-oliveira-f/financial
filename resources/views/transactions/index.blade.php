@extends('financial::layouts.master', ['title' => 'Transações'])
@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/src/table/datatable/datatables.css') }}">
    @vite(['resources/scss/light/plugins/table/datatable/dt-global_style.scss'])
    @vite(['resources/scss/light/plugins/table/datatable/custom_dt_custom.scss'])
    @vite(['resources/scss/dark/plugins/table/datatable/dt-global_style.scss'])
    @vite(['resources/scss/dark/plugins/table/datatable/custom_dt_custom.scss'])
@endsection
@section('btn_create')
    <a href="{{ route('financial.transactions.create') }}" class="btn btn-primary">Nova Transferência</a>
@endsection

@section('content')
<div class="row layout-top-spacing">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
            <div class="table-responsive mb-4 mt-4">
                <table id="zero-config" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th class="no-content">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop de transações aqui -->
                        <tr>
                            <td>#123</td>
                            <td>Pagamento Serviço</td>
                            <td>R$ 50,00</td>
                            <td><span class="badge badge-success">Concluída</span></td>
                            <td>27/10/2023</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary">Ver</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
