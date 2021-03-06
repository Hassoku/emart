<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Auth;
class LoginController extends Controller
{
    public function __construct()
{
    $this->middleware('guest:admin')->except('logout');
}
      
    public function showLoginForm()
    {
        return view('admin.auth.login',[
            'title' => 'Admin Login',
            'loginRoute' => 'admin.login',
            'forgotPasswordRoute' => 'admin.password.request',
        ]);
    }



      public function login(Request $request)
    {
        //Validation...
         $this->validator($request);
        
        //Login the admin...
          if(Auth::guard('admin')->attempt($request->only('email','password'),$request->filled('remember'))){
        //Authentication passed...
        return redirect()
            ->intended(route('admin.dashboard'))
            ->with('status','You are Logged in as Admin!');
    }
        //Redirect the admin...
        return $this->loginFailed();
    }

    /**
     * Logout the admin.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
      //logout the admin...
    	   Auth::guard('admin')->logout();
    return redirect()
        ->route('admin.login')
        ->with('status','Admin has been logged out!');
    }

    /**
     * Validate the form data.
     * 
     * @param \Illuminate\Http\Request $request
     * @return 
     */
    private function validator(Request $request)
    {
      //validate the form...
    	 $rules = [
        'email'    => 'required|email|exists:admins|min:5|max:191',
        'password' => 'required|string|min:4|max:255',
    ];

    //custom validation error messages.
    $messages = [
        'email.exists' => 'These credentials do not match our records.',
    ];

    //validate the request.
    $request->validate($rules,$messages);
    }

    /**
     * Redirect back after a failed login.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed()
    {
      //Login failed...
    	    return redirect()
        ->back()
        ->withInput()
        ->with('error','Login failed, please try again!');
    }
}
