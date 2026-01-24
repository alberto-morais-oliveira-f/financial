<?php

namespace Am2tec\Financial\Infrastructure\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Am2tec\Financial\Domain\Contracts\TransactionRepositoryInterface;

class TransactionController extends Controller
{
    protected $repository;

    public function __construct(TransactionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('financial::transactions.index');
    }

    public function create()
    {
        // Formulário de transferência
        return view('financial::transactions.create');
    }

    public function store(Request $request)
    {
        // Lógica de transferência
        return redirect()->route('financial.transactions.index');
    }

    public function show($id)
    {
        $transaction = $this->repository->findById($id);
        return view('financial::transactions.show', compact('transaction'));
    }
}
