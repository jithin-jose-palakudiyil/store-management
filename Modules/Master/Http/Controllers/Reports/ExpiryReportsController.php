<?php

namespace Modules\Master\Http\Controllers\Reports;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExpiryReportsController extends Controller
{
    public function __construct()
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->reportsUrl         =   route('reports_list');
        $this->ViewBasePath         =   'master::reports.ExpiryReports.';
        \View::share('active', 'reports');
        $this->middleware('module_permission:report-list', ['only' => ['index']]);
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug)
    {
         $array = ['expiring-items-report'];
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
         $array = ['expired-items-report','expiring-items-report'];
         if($slug != null &&  in_array($slug, $array)): 
            if($slug=='expired-items-report'):
                return $this->expiredItemsRegister($slug);
           
            elseif($slug=='expiring-items-report'):
                $request->validate([ 'from' => 'required']);
                return $this->expiringItemsRegister($request,$slug);            
            else: abort(404); endif;
            
        else: abort(404); endif;
    }
    
    /**
     * Expired Items Register download.
     * @param String $slug
     * @return Renderable
     */
    public function expiredItemsRegister($slug)
    { 
        $expired_date =\Carbon\Carbon::now()->format('Y-m-d'); 
        $ItemsList =\Modules\Master\Entities\PurchaseEntryBatch::
                select('purchase_entry.invoice_id AS invoice_id','purchase_entry_batch.amount','purchase_entry_batch.expiry_date','purchase_entry_batch.purchase_entry_id','items.name','items.id  AS item_id')
                ->join('items','items.id','=', 'purchase_entry_batch.item_id')
                ->join('purchase_entry','purchase_entry.id','=', 'purchase_entry_batch.purchase_entry_id')
                ->where('purchase_entry_batch.expiry_date', '<',$expired_date)->get(); 
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemsList,$view_blade,$slug), 'expiredItems_'.date("Ymdhisa").'.csv');
    }
    
    /**
     * Expired Items Register download.
     * @param String $slug
     * @return Renderable
     */
    public function expiringItemsRegister($request,$slug)
    { 
        $from =$request->from;
        $to =$request->to;
        $query =\Modules\Master\Entities\PurchaseEntryBatch::
                select('purchase_entry.invoice_id AS invoice_id','purchase_entry_batch.amount','purchase_entry_batch.expiry_date','purchase_entry_batch.purchase_entry_id','items.name','items.id  AS item_id')
                ->join('items','items.id','=', 'purchase_entry_batch.item_id')
                ->join('purchase_entry','purchase_entry.id','=', 'purchase_entry_batch.purchase_entry_id');
        
        
        if($request->from !=null && $request->to ==null): 
            $query->where('purchase_entry_batch.expiry_date', '>=',$from); 
        endif; 
        if($request->from !=null && $request->to !=null): 
            $query->whereBetween('purchase_entry_batch.expiry_date', [$from, $to ]);
        endif;  
        $ItemsList = $query->get(); 
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemsList,$view_blade,$slug), 'expiringItems_'.date("Ymdhisa").'.csv');
    }
    
}
