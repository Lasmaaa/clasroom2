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
        'class_info_id',
        'name',
        'description',
        'color',
        'due_date',
        'start_date',
        'status',
    ];

    protected $casts = [
        'due_date'   => 'datetime',
        'start_date' => 'datetime',
    ];

    public function classInfo()
{
    return $this->belongsTo(ClassInfo::class, 'class_info_id');
}


    // Palīdzes funkcijas
    public function isOverdue()
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               $this->status !== 'completed';
    }

    public function getFormattedDueDateAttribute()
    {
        return $this->due_date 
            ? $this->due_date->format('d.m.Y H:i') 
            : 'Nav norādīts';
    }
}