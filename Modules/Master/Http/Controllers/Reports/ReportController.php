<?php

namespace Modules\Master\Http\Controllers\Reports;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
   
    public function __construct()
    {   
 
        $this->dashboardUrl         =   route('master_dashboard');
        $this->page_title           =   'Reports';
        $this->ViewBasePath         =   'master::reports.';
        \View::share('active', 'reports');
        $this->middleware('module_permission:report-list', ['only' => ['index']]);
    
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
          return view($this->ViewBasePath.'index', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ], 
                [ "title" => 'Reports',           "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title,
            
        ]); 
        
    }

}
