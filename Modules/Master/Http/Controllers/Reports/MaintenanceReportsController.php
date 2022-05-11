<?php

namespace Modules\Master\Http\Controllers\Reports;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class MaintenanceReportsController extends Controller
{
    public function __construct()
    {   
 
        $this->dashboardUrl         =   route('master_dashboard');
        $this->reportsUrl         =   route('reports_list');
        $this->ViewBasePath         =   'master::reports.MaintenanceReport.';
        \View::share('active', 'reports');
        $this->middleware('module_permission:report-list', ['only' => ['index']]);
    }
    
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug)
    {
        $array = ['maintenance-report','item-wise-maintenance-report'];
        if($slug != null &&  in_array($slug, $array)): 
            $title =ucwords(str_replace("-"," ",$slug));
            return view($this->ViewBasePath.'forms.'.$slug, [
                'breadcrumb'    => [ 
                    [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                    [ "title" => 'Reports',             "url" => $this->reportsUrl ],
                    [ "title" =>$title,                 "url" =>  ' javascript:void(0)', "active" => 1 ]
                ], 
                'page_title'    =>  $title,'slug'=>$slug
            
        ]); 
        else: abort(404); endif;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request,$slug)
    {
       
        $array = ['maintenance-report','item-wise-maintenance-report'];
        if($slug != null &&  in_array($slug, $array)): 
            if($slug=='maintenance-report'):
                $request->validate([  'report_type' => 'required|max:255', ]);
                return $this->maintenanceReport($request, $slug);
            elseif($slug=='item-wise-maintenance-report'): 
                $request->validate([  'item' => 'required|max:255','item_id' => 'required|numeric','report_type' => 'required|max:255', ]);
                $item = \Modules\Master\Entities\Items::where("status",1)->where("id",$request->item_id)->first(); 
                if($item): return $this->itemWiseMaintenanceReport($item,$request, $slug); else: abort(404);endif;  
            endif;
            
        else: abort(404); endif;
    }
    
    /**
     * Store Wise Consumption download.
     * @param Object $store
     * @return Renderable
     */
    public function maintenanceReport($request,$slug)
    {
        $query = \Modules\Master\Entities\PivotMaintenance::with('hasOneMaintenance')->select('pivot_maintenance.*');
        if($request->exists('report_type') && $request->report_type=='completion'):
            $query->where('status',1);
            if($request->exists('from') && $request->exists('to') && $request->from!=null&& $request->to!=null):
                $query->whereBetween('completion_date', [$request->from, $request->to]);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from!=null&& $request->to == null):
                $query->where('completion_date','>=',$request->from);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from==null&& $request->to != null):
                $query->where('completion_date','<=',$request->to);
            endif;
        elseif($request->exists('report_type') && $request->report_type=='due'):
            $query->where('status',0);
            if($request->exists('from') && $request->exists('to') && $request->from!=null&& $request->to!=null):
                $query->whereBetween('date', [$request->from, $request->to]);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from!=null&& $request->to == null):
                $query->where('date','>=',$request->from);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from==null&& $request->to != null):
                $query->where('date','<=',$request->to);
            endif;
        endif;
        $maintenance = $query->get(); 
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($maintenance,$view_blade,$slug), 'maintenance_'.$request->report_type.'_'.date("Ymdhisa").'.csv');
    }
    
    
    
    /**
     * Store Wise Consumption download.
     * @param Object $store
     * @return Renderable
     */
    public function itemWiseMaintenanceReport($item,$request,$slug)
    {
        $query = \Modules\Master\Entities\PivotMaintenance::select('pivot_maintenance.*') 
                ->join('maintenance','maintenance.id','=', 'pivot_maintenance.maintenance_id')
                ->join('batch_items','batch_items.id','=', 'maintenance.batch_item_id')
                ->where('batch_items.item_id',$item->id);
        if($request->exists('report_type') && $request->report_type=='completion'):
            $query->where('pivot_maintenance.status',1);
            if($request->exists('from') && $request->exists('to') && $request->from!=null&& $request->to!=null):
                $query->whereBetween('pivot_maintenance.completion_date', [$request->from, $request->to]);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from!=null&& $request->to == null):
                $query->where('pivot_maintenance.completion_date','>=',$request->from);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from==null&& $request->to != null):
                $query->where('pivot_maintenance.completion_date','<=',$request->to);
            endif;
        elseif($request->exists('report_type') && $request->report_type=='due'):
            $query->where('pivot_maintenance.status',0);
            if($request->exists('from') && $request->exists('to') && $request->from!=null&& $request->to!=null):
                $query->whereBetween('pivot_maintenance.date', [$request->from, $request->to]);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from!=null&& $request->to == null):
                $query->where('pivot_maintenance.date','>=',$request->from);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from==null&& $request->to != null):
                $query->where('pivot_maintenance.date','<=',$request->to);
            endif;
        endif;
        $maintenance = $query->get();  
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($maintenance,$view_blade,$slug), 'maintenance_'.$request->report_type.'_'.date("Ymdhisa").'.csv');
    }
    
    
    
}
