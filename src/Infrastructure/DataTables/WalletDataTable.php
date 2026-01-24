<?php

namespace Am2tec\Financial\Infrastructure\DataTables;

use Am2tec\Financial\Infrastructure\Persistence\Models\WalletModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WalletDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (WalletModel $wallet) {
                return view('financial::wallets.datatables.actions', ['model' => $wallet]);
            })
            ->editColumn('balance', fn (WalletModel $wallet) => $wallet->balance->format())
            ->editColumn('type', fn (WalletModel $wallet) => $wallet->type->getLabel())
            ->setRowId('id');
    }

    public function query(WalletModel $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('wallets-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name')->title('Nome'),
            Column::make('balance')->title('Saldo'),
            Column::make('type')->title('Tipo'),
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
        return 'Wallets_' . date('YmdHis');
    }
}
