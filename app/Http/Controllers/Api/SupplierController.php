<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelCommon;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class SupplierController extends Controller
{
    /**
     * List all models of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $suppliers = Supplier::all();
        return response()->json( [ 'message' => 'Список поставщиков', 'data' => $suppliers ] );
    }


    /** Store incoming "create new supplier" form.
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        $supplier = new Supplier();
        $validation = $supplier->validate_data( true );

        if ( isset( $validation['failed'] ) ) {
            return $validation['validation_failed_json_response'];
        }

        $supplier->model_load_and_save();

        return response()->json( [ 'message' => 'Поставщик успешно сохранен.' ], 201 );
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $supplier = Supplier::query()->findOrFail( $id );

        return response()->json( [ 'message' => 'Успешно!', 'data' => $supplier ] );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id): JsonResponse
    {
        $supplier = Supplier::query()->findOrFail( $id );
        $validation = $supplier->validate_data();

        if ( isset( $validation['failed'] ) ) {
            return $validation['validation_failed_json_response'];
        }

        $supplier->model_load_and_save();

        return response()->json( [ 'message' => 'Данные успешно обновлены.' ], 202 );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $supplier = Supplier::query()->findOrFail($id);
        $photo_url_path = $supplier['photo'];

        if ( !$supplier->delete() ) {
            return response()->json([
                'message' => 'Ошибка, не удалено!',
                'data'    => ['id' => $id, 'deleted' => false],
            ], 500);
        }

        $this->delete_photo($photo_url_path);

        return response()->json([
            'message' => 'Поставщик удален.',
            'data'    => ['id' => $id, 'deleted' => true]
        ]);
    }

}
