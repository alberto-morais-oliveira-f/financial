<a href="{{ route('financial.wallets.edit', $model->id) }}" class="btn btn-primary btn-sm">Editar</a>
<form action="{{ route('financial.wallets.destroy', $model->id) }}" method="POST" style="display: inline-block;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
</form>
