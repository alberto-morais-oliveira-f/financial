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
        ->languageInfo('Exibindo _PAGE_ página de _PAGES_')
        ->languageSearch('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>')
        ->languageSearchPlaceholder('Procurar...')
        ->languageLengthMenu('_MENU_ Por Página')
        ->addTableClass('table table-striped table-hover')
        ->setTableId('rewards-table') // Lembre-se de alterar o ID para cada tabela (ex: 'users-table')
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
```
