<?php

namespace App\Models\Settings;

use App\Models\Finance\Expense;
use App\Models\Finance\StudentPayment;
use App\Models\HR\Employee;
use App\Models\HR\EmployeeActivities;
use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function expenses():HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function studentPayments():HasMany
    {
        return $this->hasMany(StudentPayment::class);
    }

    public function employees():HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function employeeActivities():HasMany
    {
        return $this->hasMany(EmployeeActivities::class);
    }
    public function student():HasMany
    {
        return $this->hasMany(Student::class);
    }
}
