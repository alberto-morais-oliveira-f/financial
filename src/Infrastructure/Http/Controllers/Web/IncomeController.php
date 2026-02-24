<?php

namespace Am2tec\Financial\Infrastructure\Http\Controllers\Web;

use Am2tec\Financial\Infrastructure\DataTables\IncomeDataTable;
use Illuminate\Routing\Controller;

class IncomeController extends Controller
{
    public function index(IncomeDataTable $dataTable)
    {
        return $dataTable->render('financial::incomes.index');
    }
}
