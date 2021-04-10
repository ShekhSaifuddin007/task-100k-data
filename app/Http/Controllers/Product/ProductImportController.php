<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImportRequest as Request;
use App\Http\Resources\ProductResource;
use App\Managers\ProductImportManager as Manager;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductImportController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Product::with(['category', 'brand', 'group'])
            ->paginate(1000);

        return ProductResource::collection($products);
    }

    public function import(Request $request, Manager $manager): JsonResponse
    {
        $manager->file($request->file('products'))
            ->save('product/importable');

        return response()->json("Product upload successfully.!");
    }
}
