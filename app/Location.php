<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $hidden =[
        'id',
        'updated_at',
        'created_at'
    ];
}
