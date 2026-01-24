@extends('layouts.master', ['title' => 'Demonstrativo de Resultados (DRE)'])

@section('content')
    <div class="row">
        <div class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Filtros do Relatório</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <form action="{{ route('financial.reports.dre') }}" method="GET">
                        <div class="input-group">
                            <span class="input-group-text">Início:</span>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ $startDate ?? old('start_date') }}">
                            <span class="input-group-text">Fim:</span>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ $endDate ?? old('end_date') }}">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="filter"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (isset($data))
        <div class="row">
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Resultados do Período</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>(=) Receita Operacional Bruta</td>
                                        <td class="text-end text-success">
                                            {{ 'R$ ' . number_format($data['summary']['revenue'], 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>(-) Custos</td>
                                        <td class="text-end text-danger">
                                            {{ 'R$ ' . number_format($data['summary']['costs'], 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td><strong>(=) Resultado Bruto</strong></td>
                                        <td class="text-end">
                                            <strong>{{ 'R$ ' . number_format($data['summary']['gross_profit'], 2, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>(-) Despesas Operacionais</td>
                                        <td class="text-end text-danger">
                                            {{ 'R$ ' . number_format($data['summary']['expenses'], 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>(-) Impostos</td>
                                        <td class="text-end text-danger">
                                            {{ 'R$ ' . number_format($data['summary']['taxes'], 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-dark">
                                        <td><strong>(=) Resultado Líquido (Lucro/Prejuízo)</strong></td>
                                        <td class="text-end">
                                            <strong>{{ 'R$ ' . number_format($data['summary']['operating_profit'], 2, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Detalhado</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Categoria</th>
                                        <th>Subcategoria</th>
                                        <th class="text-end">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['detailed'] as $detail)
                                        <tr>
                                            <td>{{ $detail['category_name'] }}</td>
                                            <td>{{ $detail['subcategory_name'] }}</td>
                                            <td
                                                class="text-end {{ $detail['category_type'] === 'revenue' ? 'text-success' : 'text-danger' }}">
                                                {{ 'R$ ' . number_format($detail['total_amount'], 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Nenhum dado detalhado para o período.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
