<?php

namespace Modules\Master\Http\Controllers\Reports;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LicenseReportsController extends Controller
{
    public function __construct()
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->reportsUrl         =   route('reports_list');
        $this->ViewBasePath         =   'master::reports.LicenseReports.';
        \View::share('active', 'reports');
        $this->middleware('module_permission:report-list', ['only' => ['index']]);
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug)
    {
         $array = ['license-renewed-in-range-report'];
         if($slug != null &&  in_array($slug, $array)): 
            
                $title =ucwords(str_replace("-"," ",$slug));
                return view($this->ViewBasePath.'forms.'.$slug, [
                    'breadcrumb'    => [ 
                        [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
                        [ "title" => 'Reports',        "url" => $this->reportsUrl ],
                        [ "title" =>$title,           "url" =>  ' javascript:void(0)', "active" => 1 ]
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
         $array = ['license-renewed-report','license-renewed-in-range-report'];
         if($slug != null &&  in_array($slug, $array)): 
            if($slug=='license-renewed-report'):
                return $this->licenseRenewedRegister($slug);
           
            elseif($slug=='license-renewed-in-range-report'):
                $request->validate([ 'from' => 'required']);
                return $this->licenseRenewedInRangeRegister($request,$slug);            
            else: abort(404); endif;
            
        else: abort(404); endif;
    }
    
    /**
     * Licence Renewed Register download.
     * @param String $slug
     * @return Renderable
     */
    public function licenseRenewedRegister($slug)
    { 
       
        $ItemsList = \Modules\Master\Entities\LicenceRenewal::
                select('batch_items.unique_id','items.name','items.id as item_id','licence_renewal.licence_no','licence_renewal.expiry_date','licence_renewal.renewed_date')
                 ->where('licence_renewal.renewed_date','!=',null)
                ->join('batch_items','batch_items.id','=', 'licence_renewal.batch_item_id')
                ->join('items','items.id','=', 'batch_items.item_id')
                ->orderBy('licence_renewal.renewed_date', 'DESC')->get(); 
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemsList,$view_blade,$slug), 'licenseRenewed_'.date("Ymdhisa").'.csv');
    }
    
    /**
     * Expired Items Register download.
     * @param String $slug
     * @return Renderable
     */
    public function licenseRenewedInRangeRegister($request,$slug)
    { 
        $from =$request->from;
        $to =$request->to;
        $query =\Modules\Master\Entities\LicenceRenewal::
                select('batch_items.unique_id','items.name','items.id as item_id','licence_renewal.licence_no','licence_renewal.expiry_date','licence_renewal.renewed_date')
                 ->where('licence_renewal.renewed_date','!=',null)
                ->join('batch_items','batch_items.id','=', 'licence_renewal.batch_item_id')
                ->join('items','items.id','=', 'batch_items.item_id');
                

        if($request->from !=null && $request->to ==null):  
            $query->where('licence_renewal.renewed_date', '>=',$from); 
        endif; 
        if($request->from !=null && $request->to !=null): 
            $query->whereBetween('licence_renewal.renewed_date', [$from, $to ]);
        endif;  
        $ItemsList = $query->orderBy('licence_renewal.renewed_date', 'DESC')->get(); 
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemsList,$view_blade,$slug), 'licenseRenewedInRange_'.date("Ymdhisa").'.csv');
    }
    
}
