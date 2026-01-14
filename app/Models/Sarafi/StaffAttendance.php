<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    protected $connection = 'sarafi';
    protected $table = 'staff_attendances';    
    protected $fillable = [
        'staff_id',
        'attendance_date',
        'morning_time',
        'evening_time',
        'morning_present',
        'evening_present',
        'leave_type',
        'is_paid',
        'daily_salary',
        'note'
    ];
    
    protected $casts = [
        'morning_present' => 'boolean',
        'evening_present' => 'boolean',
        'is_paid' => 'boolean',
        'daily_salary' => 'decimal:2'
    ];
    
    public function staff()
    {
        return $this->belongsTo(Staffs::class, 'staff_id');
    }
    
    public function getAttendanceStatusAttribute()
    {
        if ($this->leave_type === 'full_day') {
            return 'مرخصی کامل';
        } elseif ($this->leave_type === 'morning') {
            return 'مرخصی صبح';
        } elseif ($this->leave_type === 'evening') {
            return 'مرخصی شام';
        } elseif ($this->morning_present && $this->evening_present) {
            return 'حاضر کامل';
        } elseif ($this->morning_present) {
            return 'حاضر صبح';
        } elseif ($this->evening_present) {
            return 'حاضر شام';
        } else {
            return 'غایب';
        }
    }
    
    public function getIsFullDayAttribute()
    {
        return $this->morning_present && $this->evening_present;
    }
    
    public function calculateDailySalary()
    {
        $staff = $this->staff;
        if (!$staff) {
            return 0;
        }
        
        $dailySalary = $staff->final_salary / 30; // معاش روزانه
        $halfDaySalary = $dailySalary / 2; // معاش نیمه روز
        
        $salary = 0;
        
        if ($this->leave_type !== 'none') {
            if ($this->is_paid) {
                // اگر مرخصی با حقوق باشد
                if ($this->leave_type === 'full_day') {
                    $salary = $dailySalary;
                } elseif ($this->leave_type === 'morning' || $this->leave_type === 'evening') {
                    $salary = $halfDaySalary;
                }
            }
        } else {
            // محاسبه بر اساس حضور
            if ($this->morning_present) {
                $salary += $halfDaySalary;
            }
            
            if ($this->evening_present) {
                $salary += $halfDaySalary;
            }
        }
        
        return round($salary, 2);
    }
    
    // متد برای محاسبه وضعیت حضور به صورت کامل
    public function getFullStatusAttribute()
    {
        if ($this->leave_type === 'full_day') {
            return 'مرخصی کامل';
        } elseif ($this->leave_type === 'morning') {
            return 'مرخصی صبح';
        } elseif ($this->leave_type === 'evening') {
            return 'مرخصی شام';
        } elseif ($this->morning_present && $this->evening_present) {
            return 'حاضر کامل';
        } elseif ($this->morning_present) {
            return 'حاضر صبح';
        } elseif ($this->evening_present) {
            return 'حاضر شام';
        } else {
            return 'غایب';
        }
    }
    
    // متد برای بررسی آیا فرد حاضر بوده یا نه
    public function getIsPresentAttribute()
    {
        return $this->morning_present || $this->evening_present;
    }
    
    // متد برای دریافت نوع مرخصی به فارسی
    public function getLeaveTypeFarsiAttribute()
    {
        $types = [
            'none' => 'بدون مرخصی',
            'morning' => 'مرخصی صبح',
            'evening' => 'مرخصی شام',
            'full_day' => 'مرخصی کامل'
        ];
        
        return $types[$this->leave_type] ?? $this->leave_type;
    }
}