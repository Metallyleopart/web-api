<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remidial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'task_id',
        'nilai_awal',
        'nilai_remidial',
        'nilai_akhir',
    ];
}
