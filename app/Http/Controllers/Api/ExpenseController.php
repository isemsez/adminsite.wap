<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $expenses = Expense::query()->orderByDesc('id')->get();
        return response()->json( ['message' => 'успешно', 'data' => $expenses] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        $validation = Expense::validate_data();
        if ( isset($validation['failed']) ) {
            return $validation['validation_failed_json_response'];
        }

        $expense = new Expense();
        $expense->model_load_and_save();

        return response()->json(['message' => 'Статья расхода создана'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $expense = Expense::query()->findOrFail($id);

        return response()->json([
            'message' => 'успешно',
            'data'    => $expense
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Expense $expense
     * @return JsonResponse
     */
    public function update(Expense $expense): JsonResponse
    {
        $validation = Expense::validate_data();
        if ( isset($validation['failed']) ) {
            return $validation['validation_failed_json_response'];
        }

        $expense->model_load_and_save();

        return response()->json(['message' => 'Сохранено',
                                 'data'    => ['id' => $expense->id]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Expense $expense
     * @return JsonResponse
     */
    public function destroy(Expense $expense): JsonResponse
    {
        if ( !$expense->delete() ) {
            return response()->json([
                'message' => 'Не удалено!',
                'data'    => ['id' => $expense->id, 'deleted' => false],
            ], 500);
        }

        return response()->json([
            'message' => 'Успешно',
            'data'    => ['id' => $expense->id, 'deleted' => true],
        ]);
    }
}
