<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClassInfo;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'user_id',
        'class_info_id',
        'title',
        'name',
        'description',
        'color',
        'completed_at',
        'due_date',
        'start_date',
        'status',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'due_date'     => 'datetime',
        'start_date'   => 'datetime',
    ];

    public function classInfo()
    {
        return $this->belongsTo(ClassInfo::class, 'class_info_id');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function submissionFor(User $user)
    {
        return $this->submissions()->where('user_id', $user->id)->first();
    }

    // Palīdzes funkcijas
    public function isOverdue()
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               $this->status !== 'completed';
    }

    public function canBeSubmitted()
    {
        return ! $this->due_date || now()->lessThanOrEqualTo($this->due_date);
    }

    public function isNotStartedYet()
    {
        return $this->start_date && $this->start_date->isFuture();
    }

    public function getFormattedStartDateAttribute()
    {
        return $this->start_date
            ? $this->start_date->format('d.m.Y H:i')
            : 'Nav norādīts';
    }

    public function getFormattedDueDateAttribute()
    {
        return $this->due_date 
            ? $this->due_date->format('d.m.Y H:i') 
            : 'Nav norādīts';
    }
}
