<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserViewLavel extends Model
{
    protected $table = 'viewlevels';
    protected $fillable = [
        'title', 'ordering', 'rules'
    ];

   
}
