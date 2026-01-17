<?php

namespace Am2tec\Financial\Application\Api\Controllers;

use Am2tec\Financial\Application\Api\Data\CategoryData;
use Am2tec\Financial\Domain\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    public function index()
    {
        $categories = $this->categoryService->all();
        return CategoryData::collection($categories);
    }
}
