<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_info_id',
        'name',
        'description',
        'color',
        'due_date',
        'start_date',
        'status',
    ];

    // ←←← ŠIS IR SVARĪGI!
    protected $casts = [
        'start_date' => 'datetime',
        'due_date'   => 'datetime',
        'completed_at' => 'datetime', // ja ir tāds lauks
    ];

    public function classInfo()
    {
        return $this->belongsTo(ClassInfo::class);
    }

    public function isOverdue()
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               $this->status === 'pending';
    }

    public function isNotStartedYet()
    {
        return $this->start_date && $this->start_date->isFuture();
    }

    // Formāti
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date 
            ? $this->start_date->format('d. F Y') 
            : 'Nav norādīts';
    }

    public function getFormattedDueDateAttribute()
    {
        return $this->due_date 
            ? $this->due_date->format('d. F Y') 
            : 'Nav norādīts';
    }
}