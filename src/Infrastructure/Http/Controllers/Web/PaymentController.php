<?php

namespace Am2tec\Financial\Infrastructure\Http\Controllers\Web;

use Am2tec\Financial\Infrastructure\DataTables\PaymentDataTable;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Am2tec\Financial\Domain\Contracts\PaymentRepositoryInterface;

class PaymentController extends Controller
{
    protected $repository;

    public function __construct(PaymentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(PaymentDataTable $dataTable)
    {
        return $dataTable->render('financial::payments.index');
    }

    public function refundForm($id)
    {
        $payment = $this->repository->findById($id);
        return view('financial::payments.refund', compact('payment'));
    }

    public function refund(Request $request, $id)
    {
        // Lógica de estorno
        return redirect()->route('financial.payments.index');
    }
}
