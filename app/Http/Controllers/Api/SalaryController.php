<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaidSalaryRequest;
use App\Http\Requests\UpdateSalaryRequest;
use App\Models\Salary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    private Salary $model;

    public function __construct(Salary $model)
    {
        $this->model = $model;
    }

    public function allSalary()
    {
        $tmp = $this->model->query()->groupBy('salary_month')->get('salary_month');

        return response()->json(['message'=>'Успешно','data'=>$tmp]);
    }

    /**
     * Create resource in storage.
     *
     * @param PaidSalaryRequest $request
     * @return JsonResponse
     */
    public function paid(PaidSalaryRequest $request): JsonResponse
    {
        $data = $request->safe()->only(['id','salary_month','salary']);

        $data = array_merge( $data, [
            'employee_id' => $data['id'],
            'salary_date' => today()->format('Y-m-d'),
            'salary_year' => today()->format('Y'),
            'amount'      => $data['salary'],
        ]);

        $created = $this->model->query()->create($data);

        if ( !$created ) {
            return response()->json(['message'=>'Неудачно!'], 500);
        }

        return response()->json(['message' => 'Успешно!'], 201);
    }


    public function viewSalary(string $id)
    {
        $salaries = $this->model->query()
            ->select(['id', 'employee_id', 'salary_month', 'salary_date', 'amount'])
            ->where('salary_month', $id)
            ->with('employee:id,name')->get();

        return response()->json(['message' => 'Успешно', 'data' => $salaries]);
    }


    public function editSalary(int $id)
    {
        $salary = $this->model->query()->with('employee:id,name,email')
            ->find($id, ['employee_id', 'amount', 'salary_date', 'salary_month']);

        return response()->json(['message' => 'Успешно', 'data' => $salary]);
    }


    public function update(UpdateSalaryRequest $request, int $id)
    {
        $data = $request->safe()->only( ['salary_month','salary'] );
        $data['amount'] = $data['salary'];
        unset( $data['salary'] );

        $updated = $this->model->query()->where('id', $id )
            ->update($data);

        if ( !$updated ) {
            return response()->json( ['message'=>'Неуспешно'], 500);
        }

        return response()->json( ['message'=>'Успешно'], 202);
    }
}

