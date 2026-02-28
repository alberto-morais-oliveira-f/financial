<a href="{{ route('financial.suppliers.edit', $model->uuid) }}" class="btn btn-primary btn-sm">Editar</a>
<form action="{{ route('financial.suppliers.destroy', $model->uuid) }}" method="POST" style="display: inline-block;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</button>
</form>
