<div class="action-btns">
    <a href="{{ route('financial.wallets.edit', $model->uuid) }}" class="action-btn btn-edit bs-tooltip me-2"
       data-toggle="tooltip" data-placement="top" title="Editar">
        <i data-feather="edit-2" class="p-1 br-8 mb-1"></i>
    </a>
    <a href="javascript:void(0);" 
       onclick="if(confirm('Tem certeza que deseja excluir esta carteira?')) { this.nextElementSibling.submit(); }" 
       class="action-btn btn-delete bs-tooltip"
       data-toggle="tooltip" data-placement="top" title="Excluir">
        <i data-feather="trash-2" class="p-1 br-8 mb-1"></i>
    </a>
    <form action="{{ route('financial.wallets.destroy', $model->uuid) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
