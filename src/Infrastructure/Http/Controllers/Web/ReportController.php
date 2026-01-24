<?php

namespace Am2tec\Financial\Infrastructure\Http\Controllers\Web;

use Am2tec\Financial\Domain\Services\DreService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    public function __construct(protected DreService $dreService)
    {
    }

    public function dre(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $dreData = $this->dreService->generate($startDate, $endDate);

        return view('financial::reports.dre', [
            'data' => $dreData,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
