<?php

namespace Am2tec\Financial\Infrastructure\DataTables;

use Am2tec\Financial\Infrastructure\Persistence\Models\PaymentModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PaymentDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (PaymentModel $payment) {
                return view('financial::payments.datatables.actions', ['model' => $payment]);
            })
            ->editColumn('amount', function (PaymentModel $payment) {
                return 'R$ ' . number_format($payment->amount / 100, 2, ',', '.');
            })
            ->editColumn('status', function (PaymentModel $payment) {
                return $payment->status->value;
            })
            ->editColumn('created_at', function (PaymentModel $payment) {
                return $payment->created_at->format('d/m/Y H:i:s');
            })
            ->setRowId('uuid');
    }

    public function query(PaymentModel $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('payments-table')
            ->dom(
                "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>" .
                "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" .
                "<'table-responsive'tr>" .
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>"
            )
            ->languageInfo('Exibindo página _PAGE_ de _PAGES_')
            ->languageInfoEmpty('Sem registros para exibir')
            ->languageEmptyTable('Sem registros para exibir')
            ->languageSearch('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>')
            ->languageSearchPlaceholder('Buscar...')
            ->languageLengthMenu('Por página:  _MENU_')
            ->lengthMenu([15, 30, 50, 100])
            ->addTableClass('table table-hover style-3 dt-table-hover')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->pageLength(30)
            ->orderBy(1)
            ->stripeClasses([])
            ->selectStyleSingle()
            ->drawCallback('function() { feather.replace(); }')
            ->languagePaginate([
                'next' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                'previous' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
            ])
            ->layout([
                'bottomEnd' => [
                    'paging' => [
                        'firstLast' => false,
                    ],
                ],
            ])
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('uuid')->title('ID')->visible(false),
            Column::make('gateway')->title('Gateway'),
            Column::make('amount')->title('Valor'),
            Column::make('status')->title('Status'),
            Column::make('created_at')->title('Data'),
            Column::computed('action')
                ->title('Ações')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Payments_' . date('YmdHis');
    }
}
