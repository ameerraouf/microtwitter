<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $table = 'tweets';
    protected $fillable = ['content','user_id'];


    // public function scopeSelection($query)
    // {
    //     return $query->select('id','content','name_' . app()->getLocale() . ' as name');
    // }

}

