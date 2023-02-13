<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EncryptCookies;
use App\Models\Employee;
use Illuminate\Database\QueryException;
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
    public function index()
    {
        $employees = Employee::all();
        return response()->json(['message'=>'success', 'employees'=>$employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
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

        // without photo
        $employee = new Employee();
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

            $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/backend/employee/'; // !!! pre & post "/"
            $filename = 'img_'.time().  '.'  . explode('/', $resized_image->mime )[1];
            // create dir if necessary
            if ( !is_dir($upload_path) ) {
                if ( !mkdir($upload_path, 0770, true) ) {
                    throw new NotWritableException(
                        "Нет нужной папки и не получилось её создать."
                    );
                }
            }
            $resized_image->save($upload_path . $filename);

            $employee['photo'] = '/backend/employee/' . $filename;  // !!! pre & post "/"
        }

        try {
            $employee->save();

        } catch (QueryException $e) {
            return response()->json([
                'message'=>'Не удалось сохранить, ошибка.',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json(['message'=>'Работник успешно сохранён.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        //
    }

}
