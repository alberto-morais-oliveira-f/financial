<div class="action-btns">
    <a href="{{ route('financial.suppliers.show', $model->uuid) }}" class="action-btn btn-view bs-tooltip me-2"
       data-toggle="tooltip" data-placement="top" title="Visualizar">
        <i data-feather="eye" class="p-1 br-8 mb-1"></i>
    </a>
    <a href="{{ route('financial.suppliers.edit', $model->uuid) }}" class="action-btn btn-edit bs-tooltip me-2"
       data-toggle="tooltip" data-placement="top" title="Editar">
        <i data-feather="edit-2" class="p-1 br-8 mb-1"></i>
    </a>
    <a href="javascript:void(0);" 
       onclick="if(confirm('Tem certeza que deseja excluir este fornecedor?')) { this.nextElementSibling.submit(); }" 
       class="action-btn btn-delete bs-tooltip"
       data-toggle="tooltip" data-placement="top" title="Excluir">
        <i data-feather="trash-2" class="p-1 br-8 mb-1"></i>
    </a>
    <form action="{{ route('financial.suppliers.destroy', $model->uuid) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
