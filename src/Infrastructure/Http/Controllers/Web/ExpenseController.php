<?php

namespace Am2tec\Financial\Infrastructure\Http\Controllers\Web;

use Am2tec\Financial\Infrastructure\DataTables\ExpenseDataTable;
use Illuminate\Routing\Controller;

class ExpenseController extends Controller
{
    public function index(ExpenseDataTable $dataTable)
    {
        return $dataTable->render('financial::expenses.index');
    }
}
