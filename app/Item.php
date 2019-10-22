<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $fillable = [
        'name',
        'rating',
        'category',
        'image',
        'reputation',
        'reputationBadge',
        'price',
        'availability',

    ];

    protected $hidden =[
        'id',
        'hotelier_id',
        'location_id',
        'updated_at',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Location');
    }

    public function location(){
        return $this->belongsTo('App\Location', 'location_id', 'id');
    }
}
