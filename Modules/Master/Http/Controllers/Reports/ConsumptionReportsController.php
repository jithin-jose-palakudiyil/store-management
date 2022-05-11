<?php

namespace Modules\Master\Http\Controllers\Reports;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ConsumptionReportsController extends Controller
{
    public function __construct()
    {   
 
        $this->dashboardUrl         =   route('master_dashboard');
        $this->reportsUrl         =   route('reports_list');
        $this->ViewBasePath         =   'master::reports.ConsumptionReport.';
        \View::share('active', 'reports');
        $this->middleware('module_permission:report-list', ['only' => ['index']]);
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug)
    {
        $array = ['store-wise-consumption-report','item-wise-consumption-report','store-and-item-wise-consumption-report'];
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
       $array = ['store-wise-consumption-report','item-wise-consumption-report','store-and-item-wise-consumption-report'];
        if($slug != null &&  in_array($slug, $array)): 
            if($slug=='store-wise-consumption-report'):
                $request->validate([  'store' => 'required|numeric', ]);
                $store = \Modules\Master\Entities\Store::where('id',$request->store)->where('status',1)->first();
                if($store): return $this->storeWiseConsumptionReport($store,$slug); else: abort(404); endif;
            
            elseif($slug=='item-wise-consumption-report'): 
                $request->validate([  'item' => 'required','item_id' => 'required|numeric', ]);
                $item = \Modules\Master\Entities\Items::where("status",1)->where("id",$request->item_id)->first(); 
                if($item): return $this->itemWiseConsumptionReport($item,$slug); else: abort(404); endif;
            
            elseif($slug=='store-and-item-wise-consumption-report'):
                $request->validate([ 'store' => 'required|numeric', 'item' => 'required','item_id' => 'required|numeric' ]);
                $store = \Modules\Master\Entities\Store::where('id',$request->store)->where('status',1)->first();
                $item = \Modules\Master\Entities\Items::where("status",1)->where("id",$request->item_id)->first(); 
                if($store && $item): return $this->storeItemWiseConsumptionReport($store,$item,$slug); else: abort(404); endif;
            
            endif;
        else: abort(404); endif;
    }

    /**
     * Store Wise Consumption download.
     * @param Object $store
     * @return Renderable
     */
    public function storeWiseConsumptionReport($store,$slug)
    {
        $ItemUsage = \Modules\Master\Entities\ItemUsage::select('item_usage.*','items.id as item_id','items.name as item_name')
                    ->join('items','items.id','=', 'item_usage.item_id')
                    ->where('store_id',$store->id)->get();
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemUsage,$view_blade,$slug), $store->name.'_'.date("Ymdhisa").'.csv');
    }
    
    
    /**
     * Store Wise Consumption download.
     * @param Object $store
     * @return Renderable
     */
    public function itemWiseConsumptionReport($item,$slug)
    {
        $ItemUsage = \Modules\Master\Entities\ItemUsage::select('item_usage.*','items.id as item_id','items.name as item_name')
                    ->join('items','items.id','=', 'item_usage.item_id')
                    ->where('item_usage.item_id',$item->id)->get();
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemUsage,$view_blade,$slug), $item->name.'_'.date("Ymdhisa").'.csv');
    }
    
    
    /**
     * Store Wise Consumption download.
     * @param Object $store
     * @return Renderable
     */
    public function storeItemWiseConsumptionReport($store,$item,$slug)
    {
        $ItemUsage = \Modules\Master\Entities\ItemUsage::select('item_usage.*','items.id as item_id','items.name as item_name')
                    ->join('items','items.id','=', 'item_usage.item_id')
                    ->where('item_usage.item_id',$item->id)
                    ->where('item_usage.store_id',$store->id)->get();
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemUsage,$view_blade,$slug), $store->name.'_'.$item->name.'_'.date("Ymdhisa").'.csv');
    }
    
    /**
     * Show the Item for a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ItemAutocomplete(Request $request)
    {
        $res = \Modules\Master\Entities\Items::
                 select('id','name')
                ->where("name","LIKE","%{$request->term}%")
                ->where("status",1)
                ->get();
    
        return response()->json($res);
    }
}
