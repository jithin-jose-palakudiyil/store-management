<?php

namespace Modules\Master\Http\Controllers\Reports;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class GatePassReportsController extends Controller
{
    public function __construct()
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->reportsUrl         =   route('reports_list');
        $this->ViewBasePath         =   'master::reports.GatePassReports.';
        \View::share('active', 'reports');
        $this->middleware('module_permission:report-list', ['only' => ['index']]);
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug)
    {
        abort(404); 
//         $array = ['license-renewed-in-range-report'];
//         if($slug != null &&  in_array($slug, $array)): 
//            
//                $title =ucwords(str_replace("-"," ",$slug));
//                return view($this->ViewBasePath.'forms.'.$slug, [
//                    'breadcrumb'    => [ 
//                        [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
//                        [ "title" => 'Reports',        "url" => $this->reportsUrl ],
//                        [ "title" =>$title,           "url" =>  ' javascript:void(0)', "active" => 1 ]
//                    ], 
//                    'page_title'    =>  $title,'slug'=>$slug
//
//                ]); 
//           
//        else: abort(404); endif;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request,$slug)
    {
         $array = ['gate-pass-report'];
         if($slug != null &&  in_array($slug, $array)): 
            if($slug=='gate-pass-report'):
                return $this->gatePassRegister($slug);
           
//            elseif($slug=='license-renewed-in-range-report'):
                            
            else: abort(404); endif;
            
        else: abort(404); endif;
    }
    
    /**
     * gate pass Renewed Register download.
     * @param String $slug
     * @return Renderable
     */
    public function gatePassRegister($slug)
    { 
       
        $ItemsList = \Modules\Master\Entities\GatePass::
                select('batch_items.unique_id','items.id as item_id','items.name as item_name','breakage.id as breakage_id','breakage.breakage_date','gate_pass.pass_date','gate_pass.name','gate_pass.email','gate_pass.contact_number','gate_pass.status')
                ->join('breakage','breakage.id','=', 'gate_pass.breakage_id')
                ->join('items','items.id','=', 'breakage.item_id')
                ->join('batch_items','batch_items.id','=', 'breakage.batch_item_id')
                ->orderBy('gate_pass.updated_at', 'DESC')->get();
  
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemsList,$view_blade,$slug), 'gatePass_'.date("Ymdhisa").'.csv');
    }
    
    
}
