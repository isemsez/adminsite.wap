<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = DB::table('products')
            ->join('categories', 'products.category_id', 'categories.id')
            ->join('suppliers', 'products.supplier_id', 'suppliers.id')
            ->select('products.*', 'categories.category_name', 'suppliers.name')
            ->orderBy('products.id', 'DESC')
            ->get();

        return response()->json(['message' => 'успешно', 'data' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(int $id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return response()->json(['message' => 'успешно', 'data' => [
            'product' => Product::query()->findOrFail($id),
            'categories' => $this->categoryDropdItems(),
            'suppliers' => $this->supplierDropdItems()
        ]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Int $id
     * @return JsonResponse
     */
    public function update(int $id)
    {
        $product = Product::query()->findOrFail($id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        //
    }

    /**
     * Get items for dropdown menus.
     *
     * @return JsonResponse
     * @throws \Throwable
     */
    public function items_category_supplier(): JsonResponse
    {
        $categoryDropdItems = $this->categoryDropdItems();
        $supplierDropdItems = $this->supplierDropdItems();

        throw_if(!$categoryDropdItems or $categoryDropdItems == array()
            or !$supplierDropdItems or $supplierDropdItems == array(),
            'No data in dropdown items.');

        return response()->json(['message' => 'успешно', 'data' => [
            'categories' => $categoryDropdItems,
            'suppliers' => $supplierDropdItems
        ]]);
    }

    /**
     * Get items for category dropdown menu.
     *
     * @return Collection
     */
    public function categoryDropdItems(): Collection
    {
        return Category::query()->get(['id', 'category_name']);
    }

    /**
     * Get items for supplier dropdown menu.
     *
     * @return Collection
     */
    public function supplierDropdItems(): Collection
    {
        return Supplier::query()->get(['id', 'shopname']);
    }
}
