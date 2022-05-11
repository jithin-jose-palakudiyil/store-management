<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function __construct()
    { 
        $this->active           =   'dashboard';
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {   
        
        $page_title = "Dashboard";$view = '';
        if(\Auth::guard(master_guard)->user()->role=='master'):$view='m';
        elseif(\Auth::guard(master_guard)->user()->role=='store'):   $view='s';
        else:abort(404); endif;
        
        $breadcrumb = array( array ("title" => 'Dashboard', "url" => URL(master_prefix), "active" => 1 ) ); 
        return view('master::dashboard.'.$view, ['page_title'=>$page_title,'breadcrumb'=>$breadcrumb,'active'=>$this->active]);
    }
}
