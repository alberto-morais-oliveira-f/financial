<?php

namespace Am2tec\Financial\Application\Api\Controllers;

use Am2tec\Financial\Application\Api\Requests\DreRequest;
use Am2tec\Financial\Domain\Services\DreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class DreController extends Controller
{
    public function __construct(protected DreService $dreService)
    {
    }

    public function show(DreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $dreData = $this->dreService->generate($validated['start_date'], $validated['end_date']);

        return response()->json($dreData);
    }
}
