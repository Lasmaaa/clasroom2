<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassInfo extends Model
{
    use HasFactory;

    protected $table = 'class_infos';

    protected $fillable = [
        'user_id',
        'class_name',
        'color',
        'class_code',
    ];

    public function tasks()
{
    return $this->hasMany(Task::class);
}
}