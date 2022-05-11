<?php

namespace Modules\Master\Http\Controllers\Barcode;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BarcodeController extends Controller
{
    
       public function __construct()
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('stock.items');
         
   
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    { 
        // barcode print view
        $batch_items =[]; 
        if($request->exists('uid') && $request->exists('item_id') && !empty($request->uid)):
             
      
                if(\Auth::guard(master_guard)->user()->role=='master'): 
                    $batch_items = \Modules\Master\Entities\BatchItems::select('batch_items.*','items.name as item_name')
                        ->where("batch_items.whs_breakage",0)
                        ->where("batch_items.item_id",$request->item_id)
                        ->join('items','items.id','=', 'batch_items.item_id')
                        ->whereNull("batch_items.deleted_at")
                        ->whereIn('batch_items.unique_id',$request->uid )
                        ->whereNotIn('batch_items.id', function ($query) use($request) {
                            $query->select('batch_items.id')
                                    ->from('pivot_store_items')
                                    ->join('store_items_list','pivot_store_items.store_item_id','=', 'store_items_list.id')
                                    ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                                    ->where("store_items_list.item_id",$request->item_id)
                                    ->where("pivot_store_items.is_recived",1); 
                        })->get(); 
                elseif(\Auth::guard(master_guard)->user()->role=='store'): 
                    $batch_items = \Modules\Master\Entities\StoreItemsList::select('batch_items.*','pivot_store_items.id as pivot_store_id','items.name as item_name')
                        ->join('pivot_store_items','pivot_store_items.store_item_id','=', 'store_items_list.id')
                        ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                        ->join('items','items.id','=', 'batch_items.item_id')
                        ->where("store_items_list.item_id",$request->item_id)
                        ->whereIn('batch_items.unique_id',$request->uid )
                        ->where("store_items_list.store_id",\Auth::guard(master_guard)->user()->store_id)
                        ->where("pivot_store_items.is_recived",1)
                        ->where("pivot_store_items.is_breakage",0)
                        ->where("batch_items.whs_breakage",0)
                        ->whereNull("pivot_store_items.deleted_at")
                        ->get(); 
                endif; 
            
    return view('master::barcode.index', [
            'breadcrumb'    =>  [ 
                [ "title" => 'Dashboard',           "url"   =>  $this->dashboardUrl ],
                [ "title" => 'Items',               "url"   =>  $this->defaultUrl],
                [ "title" => 'Barcode',             "url"   =>  'javascript:void(0)', "active" => 1 ]
            ],
           'batch_items'    =>  $batch_items
        ]);
       else: return \Redirect::back()->withErrors(['msg' => 'You need to select UID']); endif;             
        
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        // barcode reading view
        $batch_item =null; 
        if($request->exists('uid') && !empty($request->uid)):
          
                if(\Auth::guard(master_guard)->user()->role=='master'): 
                    $batch_item = \Modules\Master\Entities\BatchItems::select('batch_items.*')
//                        ->where("batch_items.whs_breakage",0)
//                        ->where("batch_items.item_id",$request->item_id)
//                        ->whereNull("batch_items.deleted_at")
                        ->where('batch_items.unique_id',$request->uid )
//                        ->whereNotIn('batch_items.id', function ($query) use($request) {
//                            $query->select('batch_items.id')
//                                    ->from('pivot_store_items')
//                                    ->join('store_items_list','pivot_store_items.store_item_id','=', 'store_items_list.id')
//                                    ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
//                                    ->where("batch_items.unique_id",$request->uid)
//                                    ->where("pivot_store_items.is_recived",1); 
//                        })
                                ->first(); 
                elseif(\Auth::guard(master_guard)->user()->role=='store'): 
                    $batch_item = \Modules\Master\Entities\StoreItemsList::select('batch_items.*','pivot_store_items.id as pivot_store_id')
                        ->join('pivot_store_items','pivot_store_items.store_item_id','=', 'store_items_list.id')
                        ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
//                        ->where("store_items_list.item_id",$request->item_id)
                        ->where('batch_items.unique_id',$request->uid )
                        ->where("store_items_list.store_id",\Auth::guard(master_guard)->user()->store_id)
                        ->where("pivot_store_items.is_recived",1)
//                        ->where("pivot_store_items.is_breakage",0)
//                        ->where("batch_items.whs_breakage",0)
//                        ->whereNull("pivot_store_items.deleted_at")
                        ->first(); 
              
                endif; 
        endif;
       
        return view('master::barcode.create', [
            'breadcrumb'    =>  [ 
                [ "title" => 'Dashboard',           "url" =>    $this->dashboardUrl ],
                [ "title" => 'Items',               "url" =>    $this->defaultUrl],
                [ "title" => 'Barcode Read',        "url" =>    'javascript:void(0)', "active" => 1 ]
            ],
           'request'=>$request,'batch_item'=>$batch_item, 'page_title'    =>  'Barcode Read',
        ]);
           
         
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function barcode_action($type,$item_id,$uid,Request $request)
    {
       if($request->ajax()): 
            $data = $this->getUidDetails($uid,$item_id);
            if($data):
                if($type=='purchase-entry'):        return $this->purchase_entry_view($data); 
                elseif($type=='maintenance'):       return $this->maintenance_view($data); 
                elseif($type=='breakdown'):         return $this->breakdown_view($data); 
                elseif($type=='licence-renewal'):   return $this->licence_renewal_view($data); 
                elseif($type=='breakage'):          return $this->breakage_view($data); 
                elseif($type=='calibration'):          return $this->calibration_view($data); 
                else: return response()->json(['html' => '<code>Sorry No Data Found!</code>']); endif; 
            else: return response()->json(['html' => '<code>Sorry No Data Found!</code>']); endif; 
        else: abort(404); endif;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getUidDetails($uid,$item_id)
    {
        $batch_item = null;
                if(\Auth::guard(master_guard)->user()->role=='master'): 
                    $batch_item = \Modules\Master\Entities\BatchItems::select('batch_items.*')
//                        ->where("batch_items.whs_breakage",0)
//                        ->where("batch_items.item_id",$item_id)
//                        ->whereNull("batch_items.deleted_at")
                        ->where('batch_items.unique_id',$uid )
//                        ->whereNotIn('batch_items.id', function ($query) use($item_id,$uid) {
//                            $query->select('batch_items.id')
//                                    ->from('pivot_store_items')
//                                    ->join('store_items_list','pivot_store_items.store_item_id','=', 'store_items_list.id')
//                                    ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
//                                    ->where("batch_items.unique_id",$uid)
//                                    ->where("store_items_list.item_id",$item_id)
//                                    ->where("pivot_store_items.is_recived",1); 
//                        })
                                ->first(); 
                elseif(\Auth::guard(master_guard)->user()->role=='store'): 
                    $batch_item = \Modules\Master\Entities\StoreItemsList::select('batch_items.*','pivot_store_items.id as pivot_store_id')
                        ->join('pivot_store_items','pivot_store_items.store_item_id','=', 'store_items_list.id')
                        ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                        ->where("store_items_list.item_id",$item_id)
                        ->where('batch_items.unique_id',$uid )
                        ->where("store_items_list.store_id",\Auth::guard(master_guard)->user()->store_id)
                        ->where("pivot_store_items.is_recived",1)
//                        ->where("pivot_store_items.is_breakage",0)
//                        ->where("batch_items.whs_breakage",0)
//                        ->whereNull("pivot_store_items.deleted_at")
                        ->first(); 
                endif; 
                
                return $batch_item;
    }

    /**
     * Show the form for editing the specified resource.
     * @param Object $record
     * @return Renderable
     */
    public function purchase_entry_view($record)
    {
        $html =  \View::make('master::barcode.actions.purchase-entry', compact('record'))->render();
        return response()->json(['html' => $html]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param Object $record
     * @return Renderable
     */
    public function maintenance_view($record)
    {
        $html =  \View::make('master::barcode.actions.maintenance-entry', compact('record'))->render();
        return response()->json(['html' => $html]);
    }
    
    /**
     * Show the form for editing the specified resource.
     * @param Object $record
     * @return Renderable
     */
    public function breakdown_view($record)
    {
        $html =  \View::make('master::barcode.actions.breakdown-entry', compact('record'))->render();
        return response()->json(['html' => $html]);
    }
    
    /**
     * Show the form for editing the specified resource.
     * @param Object $record
     * @return Renderable
     */
    public function licence_renewal_view($record)
    {
        $html =  \View::make('master::barcode.actions.licence-renewal-entry', compact('record'))->render();
        return response()->json(['html' => $html]);
    }
    
    /**
     * Show the form for editing the specified resource.
     * @param Object $record
     * @return Renderable
     */
    public function breakage_view($record)
    {
        $html =  \View::make('master::barcode.actions.breakage-entry', compact('record'))->render();
        return response()->json(['html' => $html]);
    }
    
    /**
     * Show the form for editing the specified resource.
     * @param Object $record
     * @return Renderable
     */
    public function calibration_view($record)
    {
        $html =  \View::make('master::barcode.actions.calibration-entry', compact('record'))->render();
        return response()->json(['html' => $html]);
    }
}
