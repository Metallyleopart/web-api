<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'grade_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function learning(){
        return $this->belongsTo(Learning::class);
    }
}
