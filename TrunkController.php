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
//use Illuminate\Validation\ValidationException;





class TrunkController extends Controller{

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth');
    }

	public function addTrunk(Request $request){
        $user = auth()->user();
        $info = array();
        if(isset($request->id)){
	        $trunk = Trunk::where('trunkid',$request->id)->first();
            
            $info['emode'] = true;
            $info['page_head'] = 'Edit Trunk';
        }else{
            $trunk = array();
            $info['page_head'] = 'Add Trunk';
            $info['emode'] = false;
        }

        return view('add_trunk', compact(['user', 'info','trunk']))->with('menu', 'mng-trunk');
	}

        // $user = auth()->user();
        // // $trunks = auth()->trunk();
        // $info = array('page_head'=>'Change');
        // // return view('add_trunk',compact('user','info','trunks'))->with('menu','add-trunk');
        //      // $trunks= Trunk::all();
        //        // $trunks = new Trunk;
        //        $trunks->trunkid = Trunk::get('trunkid');
        //        $trunks->trunk_name = Trunk::get('trunk_name');
        //        $trunks->status = Trunk::get('status');
        //        $trunks->save();

        //     return view('add_trunk',compact('user','info','trunks'))->with('menu','add-trunk');
            
     public function saveTrunk(Request $request){
         $user = auth()->user();
    //     // $trunks = auth()->trunk();
    //     $info = array('page_head'=>'Save Trunk');
    //         $this->validate($request, ['trunkid' => 'required']);
    //     $trunks->trunk_name = $request->trunk_name;
    //     $trunks->status = $request->status;

    //     $trunks->save();
    //     $request->session()->flash('alert-success', 'Trunk Updated');
    //     return redirect('home');
    // public function saveUser(Request $request){
      //  print_r($request->all());
        $id = $request->id;

        $created_at = date('Y-m-d H:i:s');


        if(!isset($id)) { // add mode
        	$this->trunkPreValidator($request->all())->validate();
            // $validator = $this->trunkPreValidator($request->all());

            // if ($validator->fails()) {
            //     $this->throwValidationException($request, $validator);
            // }


            $trunk = new Trunk(
                array(
                    'trunk_name' => $request->trunk_name,
                    'trunkid' => $request->trunkid,
                    'status' => $request->status
                ));


            if ($trunk->save()) {

                $this->addActionLog('New trunk added', 'Success');// Add in log

                $request->session()->flash('alert-success', 'Trunk saved.');
                return redirect('/home');
            } else {
                $request->session()->flash('alert-danger', 'Error while saving.');
            }
        }else{ // edit mode
			$this->trunkPreValidatorEdit($request->all())->validate();
            // $validator = $this->trunkPreValidatorEdit($request->all());

            // if ($validator->fails()) {
            //    $this->throwValidationException($request, $validator);
            // }

            $upd_arr['trunk_name'] = $request->trunk_name;
            $upd_arr['status'] = $request->status;
            $upd_arr['updated_at'] = $created_at;
            if($request->status == 'deleted'){
            	$upd_arr['deleted_at'] = $created_at;
            }

            if(Trunk::where('id', $id)->update($upd_arr)){
//                $package = Package::where('package_id', $request->package)->first();
//                $capacity = $package->capacity;
//                $conf_upd['capacity'] = $capacity;

                $this->addActionLog('Trunk updated', 'Success');// Add in log

                $request->session()->flash('alert-success', 'Trunk updated.');
                return redirect('/home');
            }
        }

        return back()->withInput($request->all());
    }


 private function trunkPreValidator(array $data){
        return Validator::make($data, [
            'trunk_name' => 'required|string|max:150',
            'status' => 'required',
            'trunkid' => 'required|string|max:6|unique:trunk',
        ]);
    }


    private function trunkPreValidatorEdit(array $data){
        return Validator::make($data, [
            'trunk_name' => 'required|string|max:150',
            'status' => 'required',
            'trunkid' => 'required|string|max:6|unique:trunk,trunkid,'.$data['id'],
        ]);
    }



  public function addActionLog($action, $result){
        $user = auth()->user();
        $admin_id = $user->id;
        DB::table('access_log')->insert(['userid'=>$admin_id, 'action'=>$action,'result'=>$result, 'browser'=>$_SERVER['HTTP_USER_AGENT'], 'ip' => $_SERVER['REMOTE_ADDR'], 'created_at'=>date('Y-m-d H:i:s') ]);
    }

    public function delete(){
    	
    }


    
    //
}
