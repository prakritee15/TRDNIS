<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trunk extends Model
{

	protected $table = 'trunk';
    //
    protected $fillable = [
        'trunkid', 'trunk_name', 'status',
    ];

    public function dnises() 
	{
		return $this->hasMany('App\Dnis', 'trunkid', 'trunkid');
	}

	public function statuslog()
	{
		return $this->belongsTo('App\StatusLog', 'status', 'id');
	}

}
