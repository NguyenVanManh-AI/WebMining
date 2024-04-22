<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    use HasFactory;

    protected $table = 'user_datas';
    
    protected $fillable = [
        'id',
        'id_user',
        'recent_care',
        'recent_add',
        'recent_buy',
    ];

}

