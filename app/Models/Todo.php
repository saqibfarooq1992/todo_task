<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'completion_time',
    ];
    public function users()
    {
        return $this->belongsTo('App\Models\User' , 'user_id');
    }
}
