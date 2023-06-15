<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::query()->get();
        return response()->json( [ 'message' => 'Успешно.', 'data' => $categories ] );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $category = new Category();
        $validation = $category->validate();

        if ( isset( $validation['failed'] ) ) {
            return response()->json( [ $validation['validation_failed_jsonresponse'] ] );
        }

        $category->query()->create( $request->all() );

        return response()->json( [ 'message' => 'Новая категория создана' ], 201 );
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $category = Category::query()->findOrFail( $id );

        return response()->json( [ 'message' => 'Успешно', 'data' => $category ] );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id): JsonResponse
    {
        $category = Category::query()->findOrFail( $id );
        $validation = $category->validate();

        if ( isset( $validation['failed'] ) ) {
            return $validation['validation_failed_jsonresponse'];
        }

        $category->model_load_and_save();

        return response()->json( [ 'message' => 'Успешно сохранено.' ],202 );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if ( !Category::query()->findOrFail( $id )->delete() ) {
            return response()->json(['message'=>'Не удалено!'], 501);
        }

        return response()->json( [ 'message' => 'Успешно удалено.' ] );
    }
}
