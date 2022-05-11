<?php

namespace Modules\Master\Http\Controllers\Profile;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use \View; use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;
class ActivityLogController extends Controller
{
    protected $repository;
    public function __construct()
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('master_activity_log'); 
        $this->page_title           =   'Activity Log';
        $this->ViewBasePath         =   'master::profile.activity_log.';
        View::share('active', 'activity-log');
        
    }
   
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $activity_log = Activity::where('causer_type','master')->where('causer_id',\Auth::guard(master_guard)->user()->id)->latest()->paginate(10);
        
          return view($this->ViewBasePath.'index', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',        "url" =>  $this->dashboardUrl ],
                [ "title" => 'Activity Log',     "url" =>  $this->defaultUrl,  "active" => 1 ]
            ], 
            'page_title'        =>  $this->page_title, "activity_log"=>$activity_log
        ]);
    }

     
}
