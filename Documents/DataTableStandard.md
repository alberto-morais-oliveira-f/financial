# Padrão para o método html() em classes DataTable

Todas as novas classes `DataTable` devem utilizar a configuração abaixo em seu método `html()` para garantir a consistência visual e de funcionalidades da tabela em todo o projeto.

Este padrão inclui:
- Traduções para o português.
- Ícones do Feather Icons para busca e paginação.
- Classes de estilo (`table-striped`, `table-hover`).
- Layout de paginação customizado.
- Botões de exportação padrão.

## Código Padrão

```php
public function html(): HtmlBuilder
{
return $this->builder()
            ->setTableId('students-table')
            ->dom(
                "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>".
                "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>".
                "<'table-responsive'tr>".
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
```
