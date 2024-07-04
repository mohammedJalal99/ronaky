<?php

namespace App\Models\HR;

use App\Models\Settings\Currency;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function currency():BelongsTo{
        return $this->belongsTo(Currency::class);
    }

    public function employeeActivities():HasMany
    {
        return $this->hasMany(EmployeeActivities::class);
    }

    public static function getTypeAmount($employee_id,$from,$to,$type):float{
        return EmployeeActivities::where('employee_id',$employee_id)
            ->where('created_at','>=',Carbon::parse($from))
            ->where('created_at','<=',Carbon::parse($to))
            ->where('type',$type)
            ->sum('amount');
    }

    public function getLastSalaryAttribute():Carbon
    {
        return Carbon::parse($this->employeeActivities()->where('type','salary')->latest()->first()?->created_at??$this->hire_date)??now();
    }
}
