@extends('financial::layouts.master', ['title' => 'Pagamentos'])

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
            <div class="table-responsive mb-4 mt-4">
                <table id="zero-config" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gateway</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th class="no-content">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop de pagamentos aqui -->
                        <tr>
                            <td>#PAY-123</td>
                            <td>Stripe</td>
                            <td>R$ 100,00</td>
                            <td><span class="badge badge-success">Pago</span></td>
                            <td>27/10/2023</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning">Estornar</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
