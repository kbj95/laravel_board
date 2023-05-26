<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Boards extends Model
{
    use HasFactory, SoftDeletes;

    // 수정할 수 없게 설정
    protected $guarded = ['id', 'created_at'];

    protected $dates = ['deleted_at'];
}
