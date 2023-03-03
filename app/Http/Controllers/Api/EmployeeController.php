<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EncryptCookies;
use App\Models\Employee;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\Exception\NotWritableException;
use Intervention\Image\ImageManagerStatic as Image;

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
        return response()->json(['message'=>'success', 'employees'=>$employees]);
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
        $this->try_to_save_employee($employee);

        return response()->json(['message'=>'Работник успешно сохранён.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param numeric-string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $employee = $this->find_employee($id);

        $employee_data = [
            'name' => $employee['name'],
            'email' => $employee['email'],
            'address' => $employee['address'],
            'salary' => $employee['salary'],
            'joining_date' => explode(' ', $employee['joining_date'] )[0],
            'phone' => $employee['phone'],
            'photo' => null,
        ];

        return response()->json([
            'message' => 'success',
            'employee' => $employee_data,
            'image_path' => $employee['photo']
        ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param numeric-string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $this->validate_form($request);

        $employee = $this->find_employee($id);
        $this->fill_employee_data($employee, $request);
        $this->try_to_save_employee($employee);

        return response()->json(['message'=>'Работник успешно сохранён.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param numeric-string $id
     * @return JsonResponse
     * @throws FileNotFoundException
     */
    public function destroy(string $id): JsonResponse
    {
        $employee = $this->find_employee($id);

        if ($photo_path_relative = $employee['photo']) {
            $photo_path_absolute = public_path() . DIRECTORY_SEPARATOR . $photo_path_relative;
            if (!file_exists($photo_path_absolute)) {
                throw new FileNotFoundException('Нет такого файла '.$photo_path_relative.' .');
            }
        }

        try {
            $employee->destroy($id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Не удалось удалить работника.',
                'error' => $e->getMessage(),
            ], 500);
        }

        if ($photo_path_relative && !unlink($photo_path_absolute) ) {
            throw new FileNotFoundException('Работник удален, но его фото осталось.');
        }

        return response()->json(['message'=>'Работник удален!']);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Void
     */
    private function validate_form(Request $request)
    {
        $validator = Validator::make( $request->all(), [

            'name' => ['required', 'regex:/^[\pL ]+$/u', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'regex:/\.\pL{2,6}$/u', 'max:30', 'unique:employees'],
            'address' => ['required', 'string', 'regex:/^(\pL|,|\.|[0-9]| )+$/u', 'min:5', 'max:255'],
            'salary' => ['required', 'numeric', 'min:100', 'max:100000000'],
            'joining_date' => ['required', 'date', 'before:tomorrow'],
            'phone' => ['required', 'regex:/^\+?([0-9]|\(|\)|-| )+$/'],
            'photo' => ['string', 'regex:/^data:image\//', 'max:130000000'],  // "reader.readAsDataURL"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Проверьте ваши данные!',
                'error' => $validator->errors(),
            ], 412);
        }
    }

    private function some_function()
    {
        //
    }

    /**
     * @param Request $request
     * @param Employee $employee An employee.
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
            $resized_image = Image::make($request['photo'])->resize(null, 200,
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            );

            $server_local_path = $_SERVER['DOCUMENT_ROOT'] . '/backend/employee/';
            $this->createDir_ifNecessary($server_local_path);

            $filename = 'img_' . time() . '.' . explode('/', $resized_image->mime)[1];
            $resized_image->save($server_local_path . $filename);

            $employee['photo'] = '/backend/employee/' . $filename;
        }
    }

    /**
     * @param string $server_local_path
     * @return void
     */
    private function createDir_ifNecessary(string $server_local_path): void
    {
        if (!is_dir($server_local_path)) {
            if (!mkdir($server_local_path, 0770, true)) {
                throw new NotWritableException(
                    "Нет нужной папки и не получилось её создать."
                );
            }
        }
    }

    /**
     * Retrieve employee from Model by id. Or return error.
     *
     * @param numeric-string $id
     * @return JsonResponse|Employee
     */
    private function find_employee(string $id)
    {
        if ( !$employee = Employee::find($id) ) {
            return response()->json([
                'message' => 'Нет такого работника',
                'error' => 'Нет работника с id ' . $id . '.',
            ], 404);
        }
        return $employee;
    }

    /**
     * @param Employee $employee New or existing employee in Model.
     * @return JsonResponse|void
     */
    private function try_to_save_employee(Employee $employee)
    {
        try {
            $employee->save();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Не удалось сохранить, ошибка.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}

