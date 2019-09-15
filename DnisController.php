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





class DnisController extends Controller{

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth');
    }



	public function addDnis(Request $request){
        $user = auth()->user();
        $info = array();
        $trunk = Trunk::all();
        $status = StatusLog::all();
        if(isset($request->id)){
            $dnis = Dnis::where('id',$request->id)->first();
            $info['emode'] = true;
            $info['page_head'] = 'Edit Dnis';
        }else{
            $dnis = array();
            $info['page_head'] = 'Add Dnis';
            $info['emode'] = false;
        }

        return view('add_dnis', compact(['user', 'info','dnis','trunk','status']))->with('menu', 'add-dnis');
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
        
         public function saveDnis(Request $request){
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
        	$this->dnisPreValidator($request->all())->validate();
            // $validator = $this->trunkPreValidator($request->all());

            // if ($validator->fails()) {
            //     $this->throwValidationException($request, $validator);
            // }


            $dnis = new Dnis(
                array(

                	'dnis' => $request->dnis,
                    'trunk_name' => $request->trunk->trunk_name,
                    'status'=> $request->status,
                ));


            if ($dnis->save()) {

                $this->addActionLog('New Dnis added', 'Success');// Add in log

                $request->session()->flash('alert-success', 'Dnis saved.');
                return redirect('/mng-dnis');
            } else {
                $request->session()->flash('alert-danger', 'Error while saving.');
            }
        }else{ // edit mode
			$this->dnisPreValidatorEdit($request->all())->validate();
            // $validator = $this->trunkPreValidatorEdit($request->all());

            // if ($validator->fails()) {
            //    $this->throwValidationException($request, $validator);
            // }

             $upd_arr['dnis'] = $request->dnis;
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

                $this->addActionLog('Dnis updated', 'Success');// Add in log

                $request->session()->flash('alert-success', 'Dnis updated.');
                return redirect('/msg-dnis');
            }
        }

        return back()->withInput($request->all());
    }

  private function dnisPreValidator(array $data){
        return Validator::make($data, [
            'dnis'=> 'required|string|max:150',
            'trunk_name' => 'required|string|max:150',
            'status' => 'required',
            // 'trunkid' => 'required|string|max:6|unique:trunk,trunkid,'.$data['id'],
        ]);
    }

  private function dnisPreValidatorEdit(array $data){
        return Validator::make($data, [
            
            'trunk_name' => 'required|string|max:150',
            'status' => 'required',
            'dnis' => 'required|string|max:150',
        ]);
    }


    //
}
