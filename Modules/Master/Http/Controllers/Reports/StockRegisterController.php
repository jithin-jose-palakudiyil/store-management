<?php

namespace Modules\Master\Http\Controllers\Reports;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class StockRegisterController extends Controller
{
    
    public function __construct()
    {   
 
        $this->dashboardUrl         =   route('master_dashboard');
        $this->reportsUrl         =   route('reports_list');
        $this->ViewBasePath         =   'master::reports.StockRegister.';
        \View::share('active', 'reports');
        $this->middleware('module_permission:report-list', ['only' => ['index']]);
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug)
    {
        $array = ['store-wise-register','category-wise-register','store-and-category-wise-register','price-wise-register'];
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
        $array = ['store-wise-register','category-wise-register','store-and-category-wise-register','price-wise-register','no-stock-register','low-stock-register'];
        if($slug != null &&  in_array($slug, $array)): 
            if($slug=='store-wise-register'):
                $request->validate([  'store' => 'required|numeric', ]);
                $store = \Modules\Master\Entities\Store::where('id',$request->store)->where('status',1)->first();
                if($store): return $this->storeWiseRegister($store,$slug); else: abort(404); endif;
            elseif($slug=='category-wise-register'): 
                $request->validate([  'category' => 'required|numeric', ]);
                $category = \Modules\Master\Entities\ItemCategory::where('id',$request->category)->where('status',1)->first();
                if($category): return $this->categoryWiseRegister($category,$slug); else: abort(404); endif;
                
            elseif($slug=='store-and-category-wise-register'):
                $request->validate([ 'store' => 'required|numeric', 'category' => 'required|numeric', ]);
                $category = \Modules\Master\Entities\ItemCategory::where('id',$request->category)->where('status',1)->first();
                $store = \Modules\Master\Entities\Store::where('id',$request->store)->where('status',1)->first();
                if($store && $category): return $this->storeCategoryWiseRegister($store,$category,$slug); else: abort(404); endif;
            
            elseif($slug=='price-wise-register'):
                $request->validate([ 'min' => 'required|numeric']);
                return $this->priceWiseRegister($request,$slug); 
                 
            elseif($slug=='no-stock-register'):
                return $this->noStockRegister($slug); 
                
            elseif($slug=='low-stock-register'):
                return $this->lowStockRegister($slug); 
            else: abort(404); endif;
            
        else: abort(404); endif;
    }

    /**
     * No Stock Register download.
     * @param String $slug
     * @return Renderable
     */
    public function noStockRegister($slug)
    {
        $ItemsList = \Modules\Master\Entities\Items::where('quantity','<=',0)->get(); 
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemsList,$view_blade,$slug),  'noStock_'.date("Ymdhisa").'.csv');
      
    }
    
    /**
     * Low Stock Register download.
     * @param String $slug
     * @return Renderable
     */
    public function lowStockRegister($slug)
    {
        $ItemsList = \Modules\Master\Entities\Items::where('quantity','<',10)->get(); 
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemsList,$view_blade,$slug),  'lowStock_'.date("Ymdhisa").'.csv');
     
    }
    
    /**
     * Store Wise Register download.
     * @param Object $store
     * @return Renderable
     */
    public function storeWiseRegister($store,$slug)
    {
        $StoreItemsList = \Modules\Master\Entities\StoreItemsList::select('store_items_list.*','items.name as item_name','items.id as item_id')->join('items','items.id','=', 'store_items_list.item_id')->where('store_id',$store->id)->get();;
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($StoreItemsList,$view_blade,$slug), $store->name.'_'.date("Ymdhisa").'.csv');
    }
  
    /**
     * Category Wise Register download.
     * @param Object $store
     * @return Renderable
     */
    public function categoryWiseRegister($category,$slug)
    {
//        $ItemsList =  \DB::select("SELECT items.id as item_id,quantity + ( SELECT  SUM(store_items_list.quantity) from store_items_list INNER JOIN items on items.id =store_items_list.item_id WHERE items.category_id =$category->id and items.id = item_id) as  quantity , items.name, items.id FROM `items` WHERE category_id =".$category->id); 
         $ItemsList = \Modules\Master\Entities\Items::with('hasManyStoreItems')->where('category_id',$category->id)->where('status',1)->get();
     
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemsList,$view_blade,$slug), $category->name.'_'.date("Ymdhisa").'.csv');
    }
    
     /**
     * Store & Category Wise Register download.
     * @param Object $store
     * @return Renderable
     */
    public function storeCategoryWiseRegister($store,$category,$slug)
    {
        $ItemsList = \Modules\Master\Entities\StoreItemsList::
                select('store_items_list.*','items.name as item_name','items.id as item_id')
                ->join('items','items.id','=', 'store_items_list.item_id')
                ->where('store_items_list.store_id',$store->id)
                ->where('items.category_id',$category->id)
                ->get();;
                
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemsList,$view_blade,$slug), $store->name.'_'.$category->name.'_'.date("Ymdhisa").'.csv');
    
    }
     

     /**
     * price Wise Register download.
     * @param string $slug
     * @return Renderable
     */ 
    public function priceWiseRegister($request,$slug) {
        
      
        $min = (int)$request->min;
        $max = (int)$request->max; 
        $query =  \Modules\Master\Entities\PurchaseEntryBatch::
                select('purchase_entry.invoice_id AS invoice_id','purchase_entry_batch.amount','purchase_entry_batch.purchase_entry_id','items.name','items.id  AS item_id')
                ->join('items','items.id','=', 'purchase_entry_batch.item_id')
                ->join('purchase_entry','purchase_entry.id','=', 'purchase_entry_batch.purchase_entry_id'); 
        if($request->min !=null && $request->max ==null): 
            $query->where('purchase_entry_batch.amount', '>=',$min); 
        endif; 
        if($request->min !=null && $request->max !=null): 
            $query->whereBetween('purchase_entry_batch.amount', [$min, $max ]);
        endif;  
        $ItemsList = $query->get(); 
        $view_blade = $this->ViewBasePath.'download.'.$slug;
        return Excel::download(new \Modules\Master\Exports\ReportsExport($ItemsList,$view_blade,$slug), 'priceRange_'.date("Ymdhisa").'.csv');
    
        
    }

    
}
