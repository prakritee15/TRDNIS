<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusLog extends Model
{
	protected $table = 'status_log';

	protected $fillable = [
        'status', 'description'
    ];
    
    
}
