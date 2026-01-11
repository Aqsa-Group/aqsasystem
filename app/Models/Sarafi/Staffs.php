<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Staffs extends Model
{
    use HasFactory;

  protected $connection = 'sarafi';
    protected $table = 'staffs';

    protected $fillable = [
        'name',
        'fathername',
        'age',
        'gender',
        'phone',
        'address',
        'image',
        'id_card',
        'document',
        'job',
        'salary_amount',
        'contract_start',
        'contract_end',
        'admin_id',
        'user_id',
    ];

    protected $casts = [
        'salary_amount'   => 'decimal:3',
        'contract_start'  => 'date',
        'contract_end'    => 'date',
    ];

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function getIdCardUrlAttribute()
    {
        return $this->id_card ? Storage::url($this->id_card) : null;
    }

    public function getDocumentUrlAttribute()
    {
        return $this->document ? Storage::url($this->document) : null;
    }

    public function getFormattedSalaryAttribute()
    {
        return number_format($this->salary_amount) . ' افغانی';
    }

    public function getContractDurationAttribute()
    {
        $start = \Carbon\Carbon::parse($this->contract_start);
        $end = \Carbon\Carbon::parse($this->contract_end);
        $diff = $start->diff($end);
        
        return $diff->y . ' سال و ' . $diff->m . ' ماه';
    }
public function withdraws()
{
    return $this->hasMany(Withdraws::class, 'staff_id');
}
    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('fathername', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('job', 'like', "%{$search}%");
        });
    }

    public function scopeByGender($query, $gender)
    {
        return $gender ? $query->where('gender', $gender) : $query;
    }

    public function scopeByJob($query, $job)
    {
        return $job ? $query->where('job', 'like', "%{$job}%") : $query;
    }

    // Relationships
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper methods
    public function deleteFiles()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            Storage::disk('public')->delete($this->image);
        }
        
        if ($this->id_card && Storage::disk('public')->exists($this->id_card)) {
            Storage::disk('public')->delete($this->id_card);
        }
        
        if ($this->document && Storage::disk('public')->exists($this->document)) {
            Storage::disk('public')->delete($this->document);
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($staff) {
            $staff->deleteFiles();
        });
    }
}
