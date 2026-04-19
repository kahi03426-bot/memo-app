<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    // 保存していい項目（カラム名）を指定します
    protected $fillable = [
        'title',
        'content',
    ];
}
