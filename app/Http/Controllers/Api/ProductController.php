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
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        $validation = Product::validate_data();
        if ( isset($validation['failed']) ) {
            return $validation['validation_failed_json_response'];
        }

        $product = new Product();
        $product->model_load_and_save();

        return response()->json(['message'=>'Успешно сохранено!'], 201);
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
            'product'    => Product::query()->findOrFail($id),
            'categories' => $this->categoryDropdwItems(),
            'suppliers'  => $this->supplierDropdwItems(),
        ]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Int $id
     * @return JsonResponse
     */
    public function update(int $id): JsonResponse
    {
        $validation = Product::validate_data('update');
        if ( isset($validation['failed']) ) {
            return $validation['validation_failed_json_response'];
        }

        $product = Product::query()->findOrFail($id);
        $product->model_load_and_save();

        return response()->json([
            'message' => 'Успешно сохранено!',
            'data'    => ['id' => $id],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::query()->findOrFail($id);
        $photo_url_path = $product['photo'];

        if ( !$product->delete() ) {
            return response()->json([
                'message' => 'Ошибка, не удалено!',
                'data'    => ['id' => $id, 'deleted' => false],
            ],500);
        }

        $this->delete_photo($photo_url_path);

        return response()->json([
            'message' => 'Успешно удалено!',
            'data'    => ['id' => $id, 'deleted' => true]
        ]);
    }

    /**
     * Get items for dropdown menus.
     *
     * @return JsonResponse
     * @throws \Throwable
     */
    public function items_category_supplier(): JsonResponse
    {
        $categoryDropdwItems = $this->categoryDropdwItems();
        $supplierDropdwItems = $this->supplierDropdwItems();

        throw_if(!$categoryDropdwItems or $categoryDropdwItems == array()
            or !$supplierDropdwItems or $supplierDropdwItems == array(),
            'No data in dropdown items.');

        return response()->json(['message' => 'успешно', 'data' => [
            'categories' => $categoryDropdwItems,
            'suppliers'  => $supplierDropdwItems
        ]]);
    }

    /**
     * Get items for category dropdown menu.
     *
     * @return Collection
     */
    public function categoryDropdwItems(): Collection
    {
        return Category::query()->get(['id', 'category_name']);
    }

    /**
     * Get items for supplier dropdown menu.
     *
     * @return Collection
     */
    public function supplierDropdwItems(): Collection
    {
        return Supplier::query()->get(['id', 'shopname']);
    }
}

