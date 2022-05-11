<?php

namespace Modules\Master\Http\Controllers\Reports;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
class BreakdownReportsController extends Controller
{
    public function __construct()
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->reportsUrl         =   route('reports_list');
        $this->ViewBasePath         =   'master::reports.BreakdownReports.';
        \View::share('active', 'reports');
        $this->middleware('module_permission:report-list', ['only' => ['index']]);
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug)
    {
        $array = ['store-wise-breakdown-report','category-wise-breakdown-report','store-and-category-wise-breakdown-report','item-wise-breakdown-report'];
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
         
       $array = ['store-wise-breakdown-report','category-wise-breakdown-report','store-and-category-wise-breakdown-report','item-wise-breakdown-report'];
       if($slug != null &&  in_array($slug, $array)): 
            if($slug=='store-wise-breakdown-report'):
                $request->validate([  'store' => 'required|numeric', ]);
                $store = \Modules\Master\Entities\Store::where('id',$request->store)->where('status',1)->first();
                if($store): return $this->storeWiseBreakdownReport($store,$slug); else: abort(404); endif;
            
            elseif($slug=='category-wise-breakdown-report'): 
                $request->validate([  'category' => 'required|numeric', ]);
                $category = \Modules\Master\Entities\ItemCategory::where('id',$request->category)->where('status',1)->first();
                if($category): return $this->categoryWiseBreakdownReport($category,$slug); else: abort(404); endif;
                
            elseif($slug=='store-and-category-wise-breakdown-report'):
                $request->validate([ 'store' => 'required|numeric', 'category' => 'required|numeric', ]);
                $category = \Modules\Master\Entities\ItemCategory::where('id',$request->category)->where('status',1)->first();
                $store = \Modules\Master\Entities\Store::where('id',$request->store)->where('status',1)->first();
                if($store && $category): return $this->storeCategoryWiseBreakdownReport($store,$category,$slug); else: abort(404); endif;
           
                
            endif;
        else: abort(404); endif;
    }

    /**
     * Store Wise Breakage download.
     * @param Object $store
     * @return Renderable
     */
    public function storeWiseBreakdownReport($store,$slug)
    {
        $BreakageReport = \Modules\Master\Entities\Breakage::where('what_is','breakdown')->with('hasOneItem')->where('store_id',$store->id)->get();
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($BreakageReport,$view_blade,$slug), $store->name.'_'.date("Ymdhisa").'.csv');
    }

    
    /**
     * Category Wise Breakage download.
     * @param Object $store
     * @return Renderable
     */
    public function categoryWiseBreakdownReport($category,$slug)
    {
        $BreakageReport = \Modules\Master\Entities\Breakage::where('what_is','breakdown')
                ->with('hasOneItem')->where('items.category_id',$category->id)
                ->join('items','items.id','=', 'breakage.item_id')->get();
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($BreakageReport,$view_blade,$slug), $category->name.'_'.date("Ymdhisa").'.csv');
    
    }
     
     /**
     * Store and Category Wise Breakage download.
     * @param Object $store
     * @return Renderable
     */
    public function storeCategoryWiseBreakdownReport($store,$category,$slug)
    {
         $BreakageReport = \Modules\Master\Entities\Breakage::where('what_is','breakdown')
                ->with('hasOneItem')->where('items.category_id',$category->id)->where('breakage.store_id',$store->id)
                ->join('items','items.id','=', 'breakage.item_id')->get();
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($BreakageReport,$view_blade,$slug), $store->name.'_'.$category->name.'_'.date("Ymdhisa").'.csv');
    
    }
}
