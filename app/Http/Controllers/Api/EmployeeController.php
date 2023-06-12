<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ModelCommon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * @var string []
     */
    private $validation_rules = [
        'name'         => ['required', 'regex:/^[\p{L} ]+$/u', 'min:2', 'max:255'],
        'email'        => ['required', 'string', 'email', 'regex:/\.\pL{2,6}$/u', 'max:30', 'unique:employees'],
        'address'      => ['required', 'string', 'regex:/^(\pL|,|\.|[0-9]| )+$/u', 'min:5', 'max:255'],
        'salary'       => ['required', 'numeric', 'min:100', 'max:100000000'],
        'joining_date' => ['required', 'date', 'before:tomorrow'],
        'phone'        => ['required', 'regex:/^\+?([0-9]|\(|\)|-| )+$/'],
        'photo'        => ['string', 'regex:/^data:image\//', 'max:130000000'],  // "reader.readAsDataURL"
    ];

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $employees = Employee::all();
//        $employees['joining_date'] = explode(' ', $employees['joining_date'])[0];
        return response()->json(['message' => 'success', 'employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate_form($request);

        $employee = new Employee();
        $this->fill_employee_data($employee, $request);

        if (!$employee->save()) {
            return response()->json(['message' => 'Не удалось сохранить, ошибка.'], 500);
        }

        return response()->json(['message' => 'Работник успешно сохранён.'], 201);
    }

    /**
     * Проверка пришедших данных.
     *
     * @param  Request  $request
     * @return JsonResponse|Void
     */
    private function validate_form(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validation_rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Проверьте ваши данные!',
                'error'   => $validator->errors(),
            ], 412);
        }
    }

    /**
     * Заполнить Model данными из формы.
     *
     * @param  Request  $request
     * @param  Employee  $employee  An employee model.
     * @return Void
     */
    private function fill_employee_data(Employee &$employee, Request $request)
    {
        // without photo
        $employee['name'] = $request->name;
        $employee['email'] = $request->email;
        $employee['address'] = $request->address;
        $employee['phone'] = $request->phone;
        $employee['salary'] = $request->salary;
        $employee['joining_date'] = $request->joining_date;
        // with photo
        if ($request['photo']) {
            $photo_url_path = Helpers::save_photo($request['photo'], 'employee');
            $employee['photo'] = $photo_url_path;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  numeric-string  $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $employee = Employee::find_employee($id);
        $employee['joining_date'] = explode(' ', $employee['joining_date'])[0];

        return response()->json([
            'message'    => 'success',
            'employee'   => $employee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  numeric-string  $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $this->validate_form($request);

        $employee = Employee::find_employee($id);
        $this->fill_employee_data($employee, $request);
        ModelCommon::try_to_save_model($employee);

        return response()->json(['message' => 'Работник успешно сохранён.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  numeric-string  $id
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function destroy(string $id): JsonResponse
    {
        $employee = Employee::find_employee($id);

        if ($photo_path_relative = $employee['photo']) {
            $photo_path_absolute = public_path().DIRECTORY_SEPARATOR.$photo_path_relative;
            if (!file_exists($photo_path_absolute)) {
                throw new FileNotFoundException('Файл '.$photo_path_relative.' не найден.');
            }
        }

        try {
            $employee->destroy($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Не удалось удалить работника.',
                'error'   => $e->getMessage(),
            ], 500);
        }

        if ($photo_path_relative && !unlink($photo_path_absolute)) {
            throw new FileNotFoundException('Работник удален, но его фото осталось.');
        }

        return response()->json(['message' => 'Работник удален!']);
    }


}

