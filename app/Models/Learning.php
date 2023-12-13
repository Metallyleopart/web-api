<?php

namespace App\Models;

use App\Models\Task;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Learning extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'materi',
        'teacher_id',
        'task_id',
    ];

    public function guru(){
        return $this->hasOne(Teacher::class);
    }
}
