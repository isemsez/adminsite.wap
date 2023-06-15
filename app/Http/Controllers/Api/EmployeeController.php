<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $employees = Employee::all();
        return response()->json( [ 'message' => 'success', 'data' => $employees ] );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        $employee = new Employee();
        $validation = $employee->validate_data( true );

        if ( isset( $validation['failed'] ) ) {
            return $validation['validation_failed_json_response'];
        }

        $employee->model_load_and_save();

        return response()->json( [ 'message' => 'Работник успешно сохранён.' ], 201 );
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $employee = Employee::query()->findOrFail( $id );
        $employee['joining_date'] = explode( ' ', $employee['joining_date'] )[0];

        return response()->json( [ 'message' => 'success', 'data' => $employee ] );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id): JsonResponse
    {
        $employee = Employee::query()->findOrFail( $id );
        $validation = $employee->validate_data();

        if ( isset( $validation['failed'] ) ) {
            return $validation['validation_failed_json_response'];
        }

        $employee->model_load_and_save();

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
        $employee = Employee::query()->findOrFail( $id );
        $photo_url_path = $employee['photo'];

        if ( !$employee->delete() ) {
            return response()->json( [ 'message' => 'Не удалено!' ], 500 );
        }

        $photo_path_absolute = public_path() . '/' . $photo_url_path;

        if ( !unlink( $photo_path_absolute ) ) {
            Log::notice( "Фото не удалилось - $photo_path_absolute" );
        }

        return response()->json( [ 'message' => 'Работник удален.' ] );
    }


}

