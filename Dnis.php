<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dnis extends Model
{
    protected $table = 'dnis';
    //
    protected $fillable = [
        'dnis', 'trunkid', 'status', 'trunkid',
    ];



	public function trunk()
	{
		return $this->belongsTo('App\Trunk', 'trunkid', 'trunkid');
	}

	public function statuslog()
	{
		return $this->belongsTo('App\StatusLog', 'status', 'id');
	}

    
}
