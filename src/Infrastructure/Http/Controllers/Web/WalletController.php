<?php

namespace Am2tec\Financial\Infrastructure\Http\Controllers\Web;

use Am2tec\Financial\Domain\Contracts\WalletRepositoryInterface;
use Am2tec\Financial\Infrastructure\DataTables\WalletDataTable;
use Am2tec\Financial\Infrastructure\Http\Requests\StoreWalletRequest;
use Am2tec\Financial\Infrastructure\Http\Requests\UpdateWalletRequest;
use Am2tec\Financial\Infrastructure\Persistence\Models\WalletModel;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(private readonly WalletRepositoryInterface $repository)
    {
    }

    public function index(WalletDataTable $dataTable)
    {
        return $dataTable->render('financial::wallets.index');
    }

    public function create()
    {
        return view('financial::wallets.create', ['wallet' => new WalletModel()]);
    }

    public function store(StoreWalletRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        
        $data['owner_id'] = $user->id;
        $data['owner_type'] = get_class($user);
        $data['currency'] = config('financial.default_currency', 'BRL');

        $this->repository->create($data);

        return redirect()->route('financial.wallets.index')->with('success', 'Carteira criada com sucesso.');
    }

    public function show($id)
    {
        $wallet = $this->repository->findOrFail($id);
        return view('financial::wallets.show', compact('wallet'));
    }

    public function edit($id)
    {
        $wallet = $this->repository->findOrFail($id);
        return view('financial::wallets.edit', compact('wallet'));
    }

    public function update(UpdateWalletRequest $request, $id)
    {
        $this->repository->update($id, $request->validated());

        return redirect()->route('financial.wallets.index')->with('success', 'Carteira atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('financial.wallets.index')->with('success', 'Carteira excluída com sucesso.');
    }
}
