<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends ModelCommon
{
    protected $fillable = ['employee_id', 'amount', 'salary_date', 'salary_month', 'salary_year',];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Whether this month salary is already paid.
     *
     * @param string $month
     * @return bool
     */
    public static function the_month_is_paid(string $month): bool
    {
        $month_found = static::query()
                ->where('employee_id', '=', request('id'))
                ->where('salary_month', '=', $month)->first();
        return isset($month_found);
    }


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
