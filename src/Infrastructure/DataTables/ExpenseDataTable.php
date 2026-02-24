<?php

namespace Am2tec\Financial\Infrastructure\DataTables;

use Am2tec\Financial\Infrastructure\Persistence\Models\EntryModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (EntryModel $entry) {
                return view('financial::expenses.datatables.actions', ['model' => $entry]);
            })
            ->editColumn('amount', function (EntryModel $entry) {
                return 'R$ ' . number_format($entry->amount / 100, 2, ',', '.');
            })
            ->addColumn('category_name', function (EntryModel $entry) {
                return $entry->category?->name ?? '-';
            })
            ->addColumn('wallet_name', function (EntryModel $entry) {
                return $entry->wallet?->name ?? '-';
            })
            ->editColumn('created_at', function (EntryModel $entry) {
                return $entry->created_at->format('d/m/Y H:i:s');
            })
            ->setRowId('uuid');
    }

    public function query(EntryModel $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['category', 'wallet'])
            ->where($model->getTable() . '.type', 'DEBIT');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('expenses-table')
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
            Column::make('description')->title('Descrição'),
            Column::make('amount')->title('Valor'),
            Column::make('category_name')->title('Categoria')->name('category.name'),
            Column::make('wallet_name')->title('Carteira')->name('wallet.name'),
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
        return 'Expenses_' . date('YmdHis');
    }
}
