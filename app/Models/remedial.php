<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class remedial extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nilai',
        'nilai_gabungan',
        'task_id',
    ];
}
