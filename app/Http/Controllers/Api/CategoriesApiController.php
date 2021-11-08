<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CategoriesApiController extends Controller
{
    public function index()
    {
        $categories = Category::categoriesTree();
        return Response::json(['success' => 'Дерево категорий успешно получено.', 'data' => $categories], 200);
    }
}
