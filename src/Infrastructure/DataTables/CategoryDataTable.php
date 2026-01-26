<?php

namespace Am2tec\Financial\Infrastructure\DataTables;

use Am2tec\Financial\Infrastructure\Persistence\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Category $category) {
                return view('financial::categories.datatables.actions', ['model' => $category]);
            })
            ->addColumn('parent_name', function (Category $category) {
                return $category->parent?->name ?? '-';
            })
            ->editColumn('type', function (Category $category) {
                return $category->type->value;
            })
            ->editColumn('is_system_category', function (Category $category) {
                return $category->is_system_category ? 'Sim' : 'Não';
            })
            ->setRowId('uuid');
    }

    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery()->with('parent');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('categories-table')
            ->languageInfo('Exibindo _PAGE_ página de _PAGES_')
            ->languageSearch('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>')
            ->languageSearchPlaceholder('Procurar...')
            ->languageLengthMenu('_MENU_ Por Página')
            ->addTableClass('table table-striped table-hover')
            ->setTableId('students-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->stripeClasses([])
            ->selectStyleSingle()
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
            Column::make('name')->title('Nome'),
            Column::make('type')->title('Tipo'),
            Column::make('parent_name')->title('Categoria Pai')->orderable(false)->searchable(false),
            Column::make('description')->title('Descrição'),
            Column::make('is_system_category')->title('Sistema')->addClass('text-center'),
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
        return 'Categories_' . date('YmdHis');
    }
}
