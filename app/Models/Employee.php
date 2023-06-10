<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','email','address','phonenum','salary','joining_date','photo',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Найти работника в Employee модели или вернуть ошибку.
     *
     * @param  numeric-string  $id
     * @return JsonResponse|Employee
     */
    public static function find_employee(string $id)
    {

        if (!$employee = Employee::find($id)) {
            return response()->json([
                'message' => 'Нет такого работника',
                'error'   => 'Нет работника с id '.$id.'.',
            ], 404);
        }
        return $employee;
    }
}
