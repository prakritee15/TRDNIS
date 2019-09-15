<?php

namespace App\Http\Controllers;
use DB;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Trunk;
use App\Dnis;
use App\StatusLog;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $user = auth()->user();
        $info = array('page_head'=>'Manage Trunk');

        $trunks = Trunk::with('StatusLog')->get();

    //     //$inactive_users = User::where('status', 'inactive')->count();

    //     //$total_users = User::orderBy('id', 'DESC')->count();

    //     //$info['total_users'] = $total_users;
    //     //$info['inactive_count'] = $inactive_users;

        return view('home', compact(['user', 'info', 'trunks']))->with('menu', 'dashboard');
    //    // return view('home');
   } // }

    public function manageDnis(){


        $user = auth()->user();
        $info = array('page_head'=>'Manage DNIS');
        $dnis1 = Dnis::with('trunk','StatusLog')->get();
        // echo "<pre>";
        // print_r($dnis1);
        // echo "</pre>";
        
        // exit;
        return view('manage_dnis', compact('user', 'info','dnis1'))->with('menu', 'mng-dnis');
    }
    public function actionLog(){
        $user = auth()->user();
        $info = array('page_head'=>'Action Log');
        return view('action_log',compact('user','info'))->with('menu','action-log');
    }
    public function manageStatus(){

        $user = auth()->user();
        $info = array('page_head'=>'Manage Status');
        $status1 = StatusLog::get();
        return view('manage_status',compact('user','info','status1'))->with('menu','manage-status');
    }
    public function trunkLog(){
        $user = auth()->user();
        $info = array('page_head'=>'Trunk Log');
        return view('trunk_log',compact('user','info'))->with('menu','trunk-log');
    }
        public function dnisLog(){
        $user = auth()->user();
        $info = array('page_head'=>'DNIS Log');
        return view('dnis_log',compact('user','info'))->with('menu','dnis-log');
    }
    public function manageProfile(){
        $user = auth()->user();
        $info = array('page_head'=>'Manage Profile');
        return view('mng_profile',compact('user','info'))->with('menu','mng-profile');
    }
    public function changeNo(){
        $user = auth()->user();
        $info = array('page_head'=>'Change');
        return view('insert',compact('user','info'))->with('menu','mng-profile');
    }
     public function updateProfile(Request $request){
        $user = auth()->user();
        $info = array('page_head'=>'Update Profile');
            $this->validate($request, ['email' => 'required']);
        $user->mobile = $request->mobile;
        $user->email = $request->email;

        $user->save();
        $request->session()->flash('alert-success', 'Profile Updated');
        return redirect('mng-profile');

    }
    
}