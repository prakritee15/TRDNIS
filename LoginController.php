<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }




    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }


        $credentials = $this->credentials($request);

        //checking already logged in other system
        if(User::where('email',$credentials['email'])->exists()){
          //redirect('otp-verify');

           // if ($this->guard()->attempt($credentials, $request->has('remember'))) {
                 return $this->sendLoginResponse($request);
           //  }
        }

       
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        // $string otp = $user->otp;
        $request->session()->regenerate();
        $email = $request->input('email');

        $this->clearLoginAttempts($request);

        //generate the otp, save it in databse
        $otp = rand(111111,999999);
        User::where('email', $email)->update(['otp'=>$otp]);
        //send sms
        return view('otp_generate', compact('email'));
       // return $this->authenticated($request, $this->guard()->user())?: redirect()->intended($this->redirectPath());
    }

    public function otpGenerate(){
        $email = $user->email;
        return view('otp_generate',compact('email'));
    }

    public function otpInsert(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $user = User::where([['email', $email],['otp', $otp]])->first();
        if($user){
            //return 'otp verified';
            \Auth::login($user);
            //redirect()->intended($this->redirectPath());
            return redirect('home');
            // return $this->authenticated($request, $this->guard()->user())?: redirect()->intended($this->redirectPath());

        }else{
            $error = array('otp'=>'Invalid OTP');
            return view('otp_generate', compact('error','email'));
        }
    // $info = array('page_head'=>'VERIFY');
    // $this->validate($request,['otp'=>'required']);
    // $user=save();
    // if(isset($_POST['generate']))
    // $string = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    // $string_shuffled = str_shuffle($string);
    // $password = substr($string_shuffled, 1, 7);



    // $password = base64_encode($password);
    // $query = mysql_query("UPDATE user_login SET password='".$password."' WHERE username = '' ");
    // $qry_run = mysql_query($query);

    // $request->session()->flash('alert-sucess','Verified');
    // $user->otp = $request->otp;  
    //  return redirect('home');
 }


}
