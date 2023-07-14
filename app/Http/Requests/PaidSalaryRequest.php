<?php

namespace App\Http\Requests;

use App\Models\Salary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaidSalaryRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id'           => ['required', 'integer'],
            'salary'       => ['required', 'integer'],
            'salary_month' => ['required',

                Rule::in(["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"]),

                function($attribute,$value,$fail) {
                    if ( Salary::the_month_is_paid($value) ) {
                        $fail("За $value месяц уже заплачено.");
                    }
                }

            ],
        ];
    }

}

