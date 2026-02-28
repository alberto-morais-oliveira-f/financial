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

## Padrão para Coluna de Ações (Actions)

Para garantir a consistência nas operações de CRUD nas tabelas, siga o padrão abaixo para a coluna de ações.

### 1. Configuração no método `dataTable()`

A coluna `action` deve renderizar uma view parcial dedicada, passando o modelo atual.

```php
public function dataTable(QueryBuilder $query): EloquentDataTable
{
    return (new EloquentDataTable($query))
        ->addColumn('action', function ($model) {
            // O caminho da view deve seguir: financial::<recurso>.datatables.actions
            return view('financial::suppliers.datatables.actions', ['model' => $model]);
        })
        ->setRowId('uuid'); // Sempre use uuid como ID da linha
}
```

### 2. Configuração no método `getColumns()`

A coluna de ações deve ser computada e posicionada ao final do array.

```php
protected function getColumns(): array
{
    return [
        // ... outras colunas
        Column::computed('action')
            ->title('Ações')
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center'),
    ];
}
```

### 3. View Parcial de Ações (`datatables/actions.blade.php`)

Utilize o padrão de classes `action-btns` para garantir o alinhamento e estilo correto dos ícones. Este padrão permite habilitar/desabilitar botões condicionalmente.

```html
<div class="action-btns">
    {{-- Botão Visualizar (Opcional) --}}
    @if(isset($show) && $show)
        <a href="{{ route($route . '.show', $model->uuid) }}" class="action-btn btn-view bs-tooltip me-2"
           data-toggle="tooltip" data-placement="top" title="Visualizar">
            <i data-feather="eye" class="p-1 br-8 mb-1"></i>
        </a>
    @endif

    {{-- Botão Editar --}}
    @if(!isset($edit) || $edit)
        <a href="{{ route($route . '.edit', $model->uuid) }}" class="action-btn btn-edit bs-tooltip me-2"
           data-toggle="tooltip" data-placement="top" title="Editar">
            <i data-feather="edit-2" class="p-1 br-8 mb-1"></i>
        </a>
    @endif

    {{-- Botão Excluir --}}
    @if(!isset($delete) || $delete)
        <a href="javascript:void(0);" 
           onclick="if(confirm('Tem certeza?')) { this.nextElementSibling.submit(); }" 
           class="action-btn btn-delete bs-tooltip"
           data-toggle="tooltip" data-placement="top" title="Excluir">
            <i data-feather="trash-2" class="p-1 br-8 mb-1"></i>
        </a>
        <form action="{{ route($route . '.destroy', $model->uuid) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>
```

**Observações Importantes:**
1. **IDs Dinâmicos**: Nunca utilize IDs estáticos (como `id="model-delete"`) dentro de um loop de DataTable, pois isso quebrará a funcionalidade para todos os registros exceto o primeiro. Utilize `this.nextElementSibling.submit()` no evento `onclick`.
2. **Tooltip**: A classe `bs-tooltip` e os atributos `data-toggle="tooltip"` são necessários para os tooltips estilizados do template.
3. **Feather Icons**: As classes `p-1 br-8 mb-1` no elemento `<i>` garantem o preenchimento e arredondamento padrão do layout.

