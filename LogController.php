<?php

namespace App\Http\Controllers;
use DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Trunk;
use App\Dnis;
use App\StatusLog;

use Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function addStatus(Request $request){
        $user = auth()->user();
        $info = array();
        if(isset($request->id)){
	        $status = StatusLog::where('id',$request->id)->first();
            
            $info['emode'] = true;
            $info['page_head'] = 'Edit Trunk';
        }else{
            $trunk = array();
            $info['page_head'] = 'Add Trunk';
            $info['emode'] = false;
        }

        return view('add_status', compact(['user', 'info','trunk']))->with('menu', 'manage-status');
	}

}
