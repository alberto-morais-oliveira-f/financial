<?php

namespace Am2tec\Financial\Infrastructure\Http\Controllers\Web;

use Am2tec\Financial\Domain\Contracts\SupplierRepositoryInterface;
use Am2tec\Financial\Infrastructure\DataTables\SupplierDataTable;
use Am2tec\Financial\Infrastructure\Http\Requests\StoreSupplierRequest;
use Am2tec\Financial\Infrastructure\Http\Requests\UpdateSupplierRequest;
use Am2tec\Financial\Infrastructure\Persistence\Models\Supplier;
use Illuminate\Routing\Controller;

class SupplierController extends Controller
{
    public function __construct(private readonly SupplierRepositoryInterface $repository)
    {
    }

    public function index(SupplierDataTable $dataTable)
    {
        return $dataTable->render('financial::suppliers.index');
    }

    public function create()
    {
        return view('financial::suppliers.create', ['supplier' => new Supplier()]);
    }

    public function store(StoreSupplierRequest $request)
    {
        $this->repository->create($request->validated());

        return redirect()->route('financial.suppliers.index')->with('success', __('financial::messages.supplier_created_successfully'));
    }

    public function show($id)
    {
        $supplier = $this->repository->findOrFail($id);
        return view('financial::suppliers.show', compact('supplier'));
    }

    public function edit($id)
    {
        $supplier = $this->repository->findOrFail($id);
        return view('financial::suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, $id)
    {
        $this->repository->update($id, $request->validated());

        return redirect()->route('financial.suppliers.index')->with('success', __('financial::messages.supplier_updated_successfully'));
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('financial.suppliers.index')->with('success', __('financial::messages.supplier_deleted_successfully'));
    }
}
