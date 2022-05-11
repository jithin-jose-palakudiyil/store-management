<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth; 
use Validator; 
use Redirect;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->redirectTo = route('master_dashboard');
        $this->redirectBack = route('master_index');
    }
    
    
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::guard(master_guard)->user())  {   return Redirect::to($this->redirectTo);   }
        else   { $page_title= 'Login'; return view('master::auth.login', compact('page_title'));} 
    }

    /**
     * Login Action .
     * @param Request $request
     * @return Response
     */
    public function LoginAction(Request $request)
    {
        if ($request->isMethod('post')) 
        {
            $validator = Validator::make($request->all(), [  'username' => 'required',  'password' => 'required', ]);
            if($validator->fails()) {  return Redirect::back()->withErrors($validator);  }
            else
            {
                if (!$request->ajax()) 
                { 
                    // set the remember me cookie if the user check the box
                    $remember = ($request->exists('remember')) ? true : false;  
                    if (Auth::guard(master_guard)->attempt(['username' => $request->get('username'), 'password' => $request->get('password')], $remember)) 
                    { 
                        if( (Auth::guard(master_guard)->user()->role == 'master' || Auth::guard(master_guard)->user()->role == 'store') &&  Auth::guard(master_guard)->user()->status == '1'):
                            return Redirect::to($this->redirectTo); 
                        else:
                            Auth::guard(master_guard)->logout();
                            \Session::flush();
                            return Redirect::back()->withErrors(['message' => 'Invalid user, Permission denied!']);
                        endif;
                           
                        
                    }
                    else { return Redirect::back()->withErrors(['message' => 'Invalid username or password. Try again!']);}
                } else { return response()->json(['message' => 'Page not found!'], 404);  }
            }
        }
        else{return Redirect::to($this->redirectBack);  }
    }

    /**
     * logout 
     * @return redirect
     */
    public function logout()
    { 
        Auth::guard(master_guard)->logout();
        \Session::flush();
        return redirect($this->redirectBack);
    }
    
    public function activity_log() 
    {
        $lastActivity = \Spatie\Activitylog\Models\Activity::latest()->get();
        return $lastActivity;
    }
}
