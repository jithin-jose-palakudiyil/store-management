<?php

namespace Modules\Master\Http\Controllers\Reports;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class CalibrationReportsController extends Controller
{
    public function __construct()
    {   
 
        $this->dashboardUrl         =   route('master_dashboard');
        $this->reportsUrl         =   route('reports_list');
        $this->ViewBasePath         =   'master::reports.CalibrationReport.';
        \View::share('active', 'reports');
        $this->middleware('module_permission:report-list', ['only' => ['index']]);
    }
    
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug)
    {
        $array = ['calibration-report','item-wise-calibration-report'];
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
       
        $array = ['calibration-report','item-wise-calibration-report'];
        if($slug != null &&  in_array($slug, $array)): 
            if($slug=='calibration-report'):
                $request->validate([  'report_type' => 'required|max:255', ]);
                return $this->calibrationReport($request, $slug);
            elseif($slug=='item-wise-calibration-report'): 
                $request->validate([  'item' => 'required|max:255','item_id' => 'required|numeric','report_type' => 'required|max:255', ]);
                $item = \Modules\Master\Entities\Items::where("status",1)->where("id",$request->item_id)->first(); 
                if($item): return $this->itemWiseCalibrationReport($item,$request, $slug); else: abort(404);endif;  
            endif;
            
        else: abort(404); endif;
    }
    
    /**
     * Store Wise Consumption download.
     * @param Object $store
     * @return Renderable
     */
    public function calibrationReport($request,$slug)
    {
        $query = \Modules\Master\Entities\PivotCalibration::select('pivot_calibration.*');
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
        $calibration = $query->get(); 
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($calibration,$view_blade,$slug), 'calibration_'.$request->report_type.'_'.date("Ymdhisa").'.csv');
    }
    
    
    
    /**
     * Store Wise Consumption download.
     * @param Object $store
     * @return Renderable
     */
    public function itemWiseCalibrationReport($item,$request,$slug)
    {
       
        $query = \Modules\Master\Entities\PivotCalibration::select('pivot_calibration.*') 
                ->join('calibration','calibration.id','=', 'pivot_calibration.calibration_id')
                ->join('batch_items','batch_items.id','=', 'calibration.batch_item_id')
                ->where('batch_items.item_id',$item->id);
        if($request->exists('report_type') && $request->report_type=='completion'):
            $query->where('pivot_calibration.status',1);
            if($request->exists('from') && $request->exists('to') && $request->from!=null&& $request->to!=null):
                $query->whereBetween('pivot_calibration.completion_date', [$request->from, $request->to]);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from!=null&& $request->to == null):
                $query->where('pivot_calibration.completion_date','>=',$request->from);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from==null&& $request->to != null):
                $query->where('pivot_calibration.completion_date','<=',$request->to);
            endif;
        elseif($request->exists('report_type') && $request->report_type=='due'):
            $query->where('pivot_calibration.status',0);
            if($request->exists('from') && $request->exists('to') && $request->from!=null&& $request->to!=null):
                $query->whereBetween('pivot_calibration.date', [$request->from, $request->to]);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from!=null&& $request->to == null):
                $query->where('pivot_calibration.date','>=',$request->from);
            elseif($request->exists('from') &&  $request->exists('to') && $request->from==null&& $request->to != null):
                $query->where('pivot_calibration.date','<=',$request->to);
            endif;
        endif;
        $maintenance = $query->get();  
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($maintenance,$view_blade,$slug), 'calibration_'.$request->report_type.'_'.date("Ymdhisa").'.csv');
    }
    
    
    
}
