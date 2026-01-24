<?php

namespace Am2tec\Financial\Infrastructure\Http\Controllers\Web;

use Am2tec\Financial\Domain\Contracts\CategoryRepositoryInterface;
use Am2tec\Financial\Infrastructure\DataTables\CategoryDataTable;
use Am2tec\Financial\Infrastructure\Http\Requests\StoreCategoryRequest;
use Am2tec\Financial\Infrastructure\Http\Requests\UpdateCategoryRequest;
use Am2tec\Financial\Infrastructure\Persistence\Models\Category;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryRepositoryInterface $repository)
    {
    }

    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('financial::categories.index');
    }

    public function create()
    {
        return view('financial::categories.create', ['category' => new Category()]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->repository->create($request->validated());

        return redirect()->route('financial.categories.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function show($id)
    {
        $category = $this->repository->findOrFail($id);
        return view('financial::categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = $this->repository->findOrFail($id);
        return view('financial::categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $this->repository->update($id, $request->validated());

        return redirect()->route('financial.categories.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);

        return redirect()->route('financial.categories.index')->with('success', 'Categoria excluída com sucesso.');
    }
}
