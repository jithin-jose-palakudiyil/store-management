<?php

namespace Modules\Master\Http\Controllers\Stock;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Items;
use Modules\Master\Repositories\CommonRepository;
use \View; use Modules\Master\Helpers\Crud;
use \Exception;

class ItemsController extends Controller
{
        public function __construct(Items $Item)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('stock.items');
        $this->createUrl            =   route('stock.items.create');  
        $this->createMessage        =   'Item is created successfully.';
        $this->createErrorMessage   =   'Item is not created successfully.';
        $this->updateMessage        =   'Item is updated successfully.';
        $this->updateErrorMessage   =   'Item is not updated successfully.';
        $this->deleteMessage        =   'Item is deleted successfully.';
        $this->deleteErrorMessage   =   'Item is not deleted successfully.'; 
        $this->page_title           =   'Items';
        $this->ViewBasePath         =   'master::stock.items.';
        $this->repository           =   new CommonRepository($Item); 
        View::share('active', 'items'); 
        
        $this->middleware('module_permission:item-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:item-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:item-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:item-delete', ['only' => ['destroy']]); 
    
    }
    
     /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        $Items =[];
        if(\Auth::guard(master_guard)->user()->role=='master'):
            $Items = Items::with('hasOneMeasurements:id,short_code')->with('hasOneItemCategory:id,allow_usage,name')->latest();   
        else:   
            if(\Auth::guard(master_guard)->user()->role=='store'):
                
//SELECT DISTINCT
//                items.id,items.has_unique_id,items.name,items.status,items.category_id,items.measurement_id,store_items_list.store_id as store_id,
//               CASE WHEN items.has_unique_id !=1  THEN store_items_list.quantity
//                WHEN items.has_unique_id =1  THEN  (
//                    
//                    SELECT COUNT(*) FROM `pivot_store_items` WHERE `store_item_id` IN ( SELECT id FROM store_items_list WHERE store_items_list.store_id =1 )
//                    
//                     )
//               ELSE NULL
//                END as 'quantity' 
//                FROM `items`
//               JOIN store_items_list on store_items_list.item_id = items.id
//                WHERE store_items_list.store_id=1
                       
                       
                $Items = Items::select('items.id','items.has_unique_id','items.name','items.status','items.category_id','items.measurement_id','store_items_list.id as store_id', 'store_items_list.quantity AS quantity')
//                        \DB::raw('( CASE WHEN items.has_unique_id !=1 THEN  store_items_list.quantity  WHEN items.has_unique_id =1  THEN  ( SELECT COUNT(*) FROM pivot_store_items WHERE pivot_store_items.store_item_id IN (SELECT id FROM store_items_list WHERE store_items_list.store_id = '.\Auth::guard(master_guard)->user()->store_id.') and pivot_store_items.is_requested=0 and pivot_store_items.is_breakage=0 and pivot_store_items.deleted_at IS NULL )    ELSE 0 END) AS quantity') )
                        ->with('hasOneMeasurements:id,short_code')
                        ->with('hasOneItemCategory:id,allow_usage,name')
                        ->join('store_items_list','store_items_list.item_id','=', 'items.id') 
                        ->where('store_items_list.store_id',\Auth::guard(master_guard)->user()->store_id)
                        ->latest('items.created_at')->get();
//dd($Items);
            endif;
        endif;
         
        return \DataTables::of($Items)->make(true);   
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
       return view($this->ViewBasePath.'index', [
            'breadcrumb'    =>  [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Items',               "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','item-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Item'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','item-edit')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','item-delete')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'batchBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','item-batch')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'page_title'    =>  $this->page_title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Items $Item)
    {
         return view($this->ViewBasePath.'create', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',            "url" =>   $this->dashboardUrl ],
                [ "title" => 'Items',               "url" =>   $this->defaultUrl, ],
                [ "title" => 'Create',               "url" =>   'javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'     =>  $this->page_title .' creating',
            'Item'   =>  $Item
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|numeric',
            'measurement_id' => 'required|numeric',
            "status" => "required|numeric", 
            "has_unique_id" => "required|numeric", 
        ]);
        if(!$request->ajax()):
            Crud::store($this->repository, $request->all(),$this->createMessage,$this->createErrorMessage);
            return \Redirect::to($this->defaultUrl );
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif;
    }

    

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Items $Item)
    {
        return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Items',               "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'Item'  =>$Item
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Items $Item)
    {
        $request->validate([
            'name'=>'required|max:255',
            'category_id'=>'required|numeric',
            'measurement_id'=>'required|numeric',
            "status"     =>  "required|numeric", 
//            "has_unique_id"     =>  "required|numeric", 
        ]);
        if(!$request->ajax()):
            Crud::update($this->repository, $Item,$request->all(),$this->updateMessage,$this->updateErrorMessage);
            return \Redirect::to($this->defaultUrl );
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy( Request $request, $id)
    {
         
        if($request->exists('type') && $request->exists('unique_id') && $request->type!=null && $request->type=='uid' && $request->unique_id!=null):
            $unique_id = $request->unique_id;
            $_Item = Items::find($id);
           
            $batch_item = null;
            if(\Auth::guard(master_guard)->user()->role=='master'):
                    $batch_item = \Modules\Master\Entities\BatchItems::select('batch_items.*')
                        ->where("batch_items.whs_breakage",0)
                        ->where("batch_items.item_id",$_Item->id)
                        ->where("batch_items.unique_id",$unique_id)
                        //->whereNull("batch_items.deleted_at")
                        ->whereNotIn('batch_items.id', function ($query) use($_Item) {
                            $query->select('batch_items.id')
                                    ->from('pivot_store_items')
                                    ->join('store_items_list','pivot_store_items.store_item_id','=', 'store_items_list.id')
                                    ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                                    ->where("store_items_list.item_id",$_Item->id)
                                    ->where("pivot_store_items.is_recived",1);
//                                    ->whereNull("pivot_store_items.deleted_at");
                        })->first();
                elseif(\Auth::guard(master_guard)->user()->role=='store'): 
                    $batch_item = \Modules\Master\Entities\StoreItemsList::select('batch_items.*','pivot_store_items.id as pivot_store_id','store_items_list.id as store_item_id')
                        ->join('pivot_store_items','pivot_store_items.store_item_id','=', 'store_items_list.id')
                        ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                        ->where("store_items_list.item_id",$_Item->id)
                        ->where("store_items_list.store_id",\Auth::guard(master_guard)->user()->store_id)
                        ->where("batch_items.unique_id",$unique_id)
                        ->where("pivot_store_items.is_recived",1)
                        ->where("pivot_store_items.is_breakage",0)
                        ->where("batch_items.whs_breakage",0)
                        ->whereNull("batch_items.deleted_at")
                        ->first();
                endif; 
                
            if($batch_item):
                 if(\Request::ajax()): 
                    $error = $msg = null;
                    try{ 
                        if($batch_item):  
                            
                            if(\Auth::guard(master_guard)->user()->role=='master'):
                                
                                $_Item->update(['quantity'=>$_Item->quantity-1]);
                            
                            elseif(\Auth::guard(master_guard)->user()->role=='store'): 
                                $StoreItemsList = \Modules\Master\Entities\StoreItemsList::find($batch_item->store_item_id);
                                if($StoreItemsList):
                                    $PivotStoreItems=\Modules\Master\Entities\PivotStoreItems::where('id',$batch_item->pivot_store_id)->where('store_item_id',$StoreItemsList->id)->first();
                                    if($PivotStoreItems):
                                        $StoreItemsList->update(['quantity'=>$StoreItemsList->quantity-1]);
                                        $PivotStoreItems->update(['deleted_at'=> \Carbon\Carbon::now()]);
                                    endif; 
                                endif;
                            endif;
                            //delete item 
                           \Modules\Master\Entities\BatchItems::where('id',$batch_item->id)->update(['deleted_at'=> \Carbon\Carbon::now()]);
                        endif;
                    } catch (Exception $ex) {  $error = $ex->getMessage();  }

                    if($error == null):      
                        \Session::flash('flash-success-message',$this->deleteMessage);
                        $msg=array('type'=>'success'); 
                    else: 
                        \Session::flash('flash-error-message',$this->deleteErrorMessage);
                        $msg=array('type'=>'error'); 
                    endif;
                else:
                    \Session::flash('flash-error-message',$this->deleteErrorMessage);
                    $msg=array('type'=>'error');
                endif; 
                return response()->json($msg, 200);
            endif;
            
            
            
            
        elseif($request->exists('type') && !$request->exists('unique_id')  && $request->type!=null && $request->type=='sng' ):
             
        
            
            $Item = Items::find($id);
            if($Item->has_unique_id !=1): 
                return Crud::destroy(
                    $this->repository, $Item,$this->deleteMessage,$this->deleteErrorMessage
                );
            else:
                return response()->json(['message' => 'Page not found!'], 404);
            endif;
            
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif;
         
    }
    
    
    /**
     * Display a Measurements of the Item Category.
     * @return Response
     */
    public function getMeasurementsWithItemCategory(Request $request, $id)
    {
        $option = '<option></option>' ;;
        if( $request->ajax()):
//            $ItemCategory = $ItemCategory->with('belongsToManyMeasurements')->first(); 
            $ItemCategory = \Modules\Master\Entities\ItemCategory::with('belongsToManyMeasurements')->where('id',$id)->get()->first();
            if(isset($ItemCategory->belongsToManyMeasurements) && $ItemCategory->belongsToManyMeasurements->isNotEmpty()):
               $measurements =  $ItemCategory->belongsToManyMeasurements->all(); 
               if(!empty($measurements)):
                   foreach ($measurements as $key => $value):
                   $selected = null;
                   if($request->measurement_id !=null && $request->measurement_id ==$value->id): $selected = 'selected=""'; endif;
                       $option.= '<option '.$selected.' value="'.$value->id.'">'.$value->name.'</option>' ;
                   endforeach; 
               endif; 
            endif;
           return response()->json(['option' => $option]); 
        else:  abort(404); endif;  
    }
    
    /**
     * Display a Usage of the Item.
     * @return Response
     */
    public function getUsageModel(Request $request, $id)
    {
        if( $request->ajax()):
            $html= $item = null;
            if(\Auth::guard(master_guard)->user()->role=='store'):
                
                $StoreItemsList = \Modules\Master\Entities\StoreItemsList::where('item_id',$id)->where('store_id',\Auth::guard(master_guard)->user()->store_id)->first();
                if($StoreItemsList):
                    $item = Items::with('hasOneItemCategory:id,allow_usage')->with('hasOneMeasurements:id,short_code')->where('id',$id)->first();
                    if(isset($item->quantity)):
                        $item->quantity = $StoreItemsList->quantity;
                    endif;
                endif;   
            else:
                $item = Items::with('hasOneItemCategory:id,allow_usage')->with('hasOneMeasurements:id,short_code')->where('id',$id)->first();
            endif;
            $unique =FALSE;
            
        if($item && isset($item->hasOneItemCategory->allow_usage) && $item->hasOneItemCategory->allow_usage==1):
            $usageListModel= $this->usageListModel($item);
            $unique_id =''; 
            $class_no=5;
            $qty='';
            $readonly ='';
            if($item->has_unique_id==1): 
                $unique =TRUE; 
                $readonly ='readonly=""';
                $class_no=3;
                $qty='1';
                $unique_id='<div class="col-md-'.$class_no.'">
                                <div class="form-group ">
                                    <label for="next_date">Item Unique ID <span class="text-danger">*</span></label>
                                    <input type="text"  class="form-control" id="unique_id" name="unique_id"  placeholder="unique id" value="" >
                                </div> 
                            </div>';
            endif;
            $html = '<div id="modal_item_usage" class="modal fade" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h5 class="modal-title"><i class="icon-menu7"></i> &nbsp;'.$item->name.'</h5>
                                </div>

                                <div class="modal-body">
                                    <div class="alert alert-info alert-styled-left text-blue-800 content-group">
                                    Remaining  Quantity : <span class="text-semibold" id="remaining_quantity">'.$item->quantity.'&nbsp;'.(isset($item->hasOneMeasurements->short_code) ? $item->hasOneMeasurements->short_code : '').'</span>  
                                </div>
                                 <form id="usageEntry" action="" name="usageEntry">
                                        <input type="hidden" name="item_id" id="item_id" value="'.$id.'" >
                                        <input type="hidden" name="unique" id="unique" value="'.$unique.'" >
                                        <input  type="hidden" name="QuantityBtn" id="QuantityBtn" value ='.(isset($item->quantity) ? $item->quantity : '0').'>
                                        <input type="hidden" name="item_quantity" id="item_id" value="'.$item->quantity.'" >
                                    <div> 
                                        <div class="row"> 
                                            <div class="col-md-'.$class_no.'">
                                                <div class="form-group ">
                                                    <label for="next_date">Date <span class="text-danger">*</span></label>
                                                    <input type="text"  readonly="" class="form-control datepicker-menus" id="usage_date" name="usage_date"  placeholder="DD/MM/YYYY" value="" >
                                                </div> 
                                            </div>
'.$unique_id.'
                                            <div class="col-md-'.$class_no.'">
                                                <div class="form-group ">
                                                    <label>Usage Quantity<span class="text-danger">*</span></label>
                                                    <input type="text" '.$readonly.'  class="form-control" id="usage_quantity" name="usage_quantity"  placeholder="Usage Quantity" value="'.$qty.'" >
                                                </div> 
                                            </div> 

                                            <div class="col-md-2 ">
                                                <button type="submit" class="btn btn-primary" style="margin-top: 26px;"><i class="icon-check"></i> Save</button> 
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div id="MsgDiv"></div>
                                <hr>
                                <div id="usageListDiv">
                                '.$usageListModel.'
                                </div>

                            </div>
                        </div>
                    </div>';
            endif;
            return response()->json(['html' => $html,'unique'=>$unique]);
        else:  abort(404); endif;    
    }
    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function storeUsageModel(Request $request){
//        dd($request->all());
        $request->validate
           ([ 
                'item_id'=> ["required","numeric",function ($attribute, $value, $fail) 
                {
                    if($value):
                        $item = Items::where('status',1)->where('id',$value)->first();
                        if(!$item): $fail('The item has not found');   endif;
                    endif; 
                }
            ], 'usage_date'=>'required', 'usage_quantity'=>'required|numeric|not_in:0', ]);
        $data =[ "item_id" => $request->item_id, "usage_date" => $request->usage_date, "usage_quantity" => $request->usage_quantity ];
        $error = $usageListModel = $StoreItemsList = null;
        $item = Items::with('hasOneItemCategory:id,allow_usage')->with('hasOneMeasurements:id,short_code')->where('id',$request->item_id)->first();
        if(\Auth::guard(master_guard)->user()->role=='store'): 
                $StoreItemsList = \Modules\Master\Entities\StoreItemsList::where('item_id',$request->item_id)->where('store_id',\Auth::guard(master_guard)->user()->store_id)->first();
                if($StoreItemsList):
                    if(isset($item->quantity)):
                        $data['store_id']=\Auth::guard(master_guard)->user()->store_id;
                        $item->quantity = $StoreItemsList->quantity;  
                    endif;
                endif;   
        endif;
            
//        $item = Items::with('hasOneItemCategory:id,allow_usage')->with('hasOneMeasurements:id,short_code')->where('id',$request->item_id)->first();
        
        try { 
            
            
            $remaining_quantity ='0&nbsp;'.(isset($item->hasOneMeasurements->short_code) ? $item->hasOneMeasurements->short_code : '');
            $remaining_quantity_=$item->quantity -$request->usage_quantity;
            $error_remaining_quantity_=$item->quantity;
            $action=FALSE;
            if($remaining_quantity_>=0):
//                dd($request->all());
                if($item->has_unique_id==1):
                    //has unique id 
                    if(\Auth::guard(master_guard)->user()->role=='store'):
                        //store
                        $pivot_store_items = \Modules\Master\Entities\PivotStoreItems::select('pivot_store_items.*')
                            ->join('store_items_list','store_items_list.id','=', 'pivot_store_items.store_item_id')
                            ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                            ->where("store_items_list.item_id",$item->id)
                            ->where("pivot_store_items.is_recived",1)
                            ->where("pivot_store_items.is_breakage",0)
                            ->where("batch_items.unique_id",$request->unique_id)
                            ->first();
                        if($pivot_store_items && $StoreItemsList):
                            $data['batch_item_id']=$pivot_store_items->batch_item_id;
                            $StoreItemsList->update(['quantity'=>$remaining_quantity_]);
                            $pivot_store_items->delete();
                            $action=TRUE;
                        endif;
                         
                    else:  
                        //master
                        $unique_id = $request->unique_id;
                        $batch_items = \Modules\Master\Entities\BatchItems::select('batch_items.*')
                        ->where("batch_items.whs_breakage",0)
                        ->where("batch_items.item_id",$item->id)
                        ->where("batch_items.unique_id",$unique_id)
//                        ->whereNotIn('batch_items.id', function ($query) use($item,$unique_id) {
//                            $query->select('batch_items.id')->from('pivot_store_items')
//                                    ->join('store_items_list','pivot_store_items.store_item_id','=', 'store_items_list.id')
//                                    ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
//                                    ->whereNull("pivot_store_items.deleted_at")
//                                    ->where("pivot_store_items.is_recived",1);
////                            $query->select('batch_items.id')
////                                    ->from('pivot_store_items')
////                                    ->join('store_items_list','pivot_store_items.store_item_id','=', 'store_items_list.id')
////                                    ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
////                                    ->where("store_items_list.item_id",$item->id)
////                                    ->whereNull("pivot_store_items.deleted_at")
////                                    ->where("pivot_store_items.is_recived",1)
////                                    ->where("batch_items.unique_id",$unique_id);
//                        })
                                ->first();
                       
                        if($batch_items):
                           $_PivotStoreItems =  \Modules\Master\Entities\PivotStoreItems::
                                where("is_recived",1)->where("batch_item_id",$batch_items->id)->withTrashed()->first();
                            if(!$_PivotStoreItems):
                                $data['batch_item_id']=$batch_items->id;
//                                $item->update(['quantity'=>$remaining_quantity_]);
//                                \Modules\Master\Entities\BatchItems::where('id',$batch_items->id)->delete();
//                                $batch_items->delete();
                                $action=TRUE;
                            endif;
                          
                            
                        endif;
                    endif;
                else:
                    //not has unique id  
                    if(\Auth::guard(master_guard)->user()->role=='store'):
                        if($StoreItemsList):
                            $StoreItemsList->update(['quantity'=>$remaining_quantity_]);
                            $action=TRUE;
                        endif;
                    else:   //master
                        $item->update(['quantity'=>$remaining_quantity_]);
                        $action=TRUE;
                    endif;
                endif;
                $remaining_quantity=$remaining_quantity_.'&nbsp;'.(isset($item->hasOneMeasurements->short_code) ? $item->hasOneMeasurements->short_code : '');
            endif;
            if($action==TRUE):
               \Modules\Master\Entities\ItemUsage::create($data);
            else:
                $error='Error occurred, Try again later';
            endif;
//            dd($data);
            $usageListModel= $this->usageListModel($item);
        } catch (Exception $ex) { $error =$ex->getMessage(); }
        
        if($error == null): 
           return response()->json(['sucess' => true,'list'=>$usageListModel,'message'=>'<div class="alert alert-success no-border">
									<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
									<span class="text-semibold">Well done!</span> You successfully added the usage.
							    </div>','remaining_quantity'=>$remaining_quantity,'quantity'=>$remaining_quantity_]); 
        else: 
            
           return response()->json(['sucess' => false,'list'=>$usageListModel,'message'=>'<div class="alert alert-danger alert-bordered">
									<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
									<span class="text-semibold">Oh snap!</span> Change a few things up and try submitting again.
							    </div>','remaining_quantity'=>$error_remaining_quantity_,'quantity'=>$remaining_quantity_]);  
        endif;
        
    }
    /**
     * Display a List of the Item usage.
     * @return Response
     */
    
    public function usageListModel($item){
        
        $ItemUsage = [];
        if(\Auth::guard(master_guard)->user()->role=='store'):  
            $ItemUsage = \Modules\Master\Entities\ItemUsage::where('item_id',$item->id)->where('store_id',\Auth::guard(master_guard)->user()->store_id)->orderBy('id', 'DESC')->get();;
        elseif(\Auth::guard(master_guard)->user()->role=='master'):  
            $ItemUsage = \Modules\Master\Entities\ItemUsage::where('item_id',$item->id)->orderBy('id', 'DESC')->whereNull('store_id')->get();; 
        endif; 
        $html='';
        if($ItemUsage->isNotEmpty()):
            
            $html.='<div class="table-responsive" style="max-height: 280px;">
                <table class="table table-bordered table-framed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Usage</th>'; 
            if($item->has_unique_id==1):
                 $html.='<th>Unique ID</th>';
            endif;
                                $html.='</tr>
                            </thead>
                        <tbody>';
            foreach ($ItemUsage as $key => $value) :
            $html.='<tr>
                        <td>'.($key+1).'</td>
                        <td>'.$value->usage_date.'</td>
                        <td>'.$value->usage_quantity.'&nbsp;'.(isset($item->hasOneMeasurements->short_code) ? $item->hasOneMeasurements->short_code : '').'</td>';
            if($item->has_unique_id==1):
                $BatchItems = \Modules\Master\Entities\BatchItems::find($value->batch_item_id);
                $_unique_id='';
                if($BatchItems && isset($BatchItems->unique_id)):
                    $_unique_id=$BatchItems->unique_id;
                endif;
                $html.='<td>'.$_unique_id.'</td>';
            endif;
                     $html.='</tr>';
            endforeach;
            $html.='</tbody></table></div>';
        endif;
        return $html;
    }       
     /**
     * Display a Usage of the Item.
     * @return Response
     */
    public function getBatchModel(Request $request, $id)
    {
        if( $request->ajax()):
            $html=null;
            $purchase_entry_batch = \Modules\Master\Entities\PurchaseEntryBatch::with('hasOnePurchaseEntry')->with('hasOneItem')->with('hasManyBatchItems')->where('item_id',$id)->orderBy('id', 'DESC')->get();
            $html =  \View::make($this->ViewBasePath.'model', compact('purchase_entry_batch'))->render();
            return response()->json(['html' => $html]);
        else:  abort(404); endif;    
    }
    
    /**
     * Display a Usage of the Item.
     * @return Response
     */
    public function getDeleteModel(Request $request, $id)
    {
        if( $request->ajax()):
            $html=null;
            $item = Items::where('id',$id)->first();
//            $purchase_entry_batch = \Modules\Master\Entities\PurchaseEntryBatch::with('hasOnePurchaseEntry')->with('hasOneItem')->with('hasManyBatchItems')->where('item_id',$id)->orderBy('id', 'DESC')->get();
            $html =  \View::make($this->ViewBasePath.'delete_model', compact('item'))->render();
            return response()->json(['html' => $html]);
        else:  abort(404); endif;    
    }
    
     /**
     * Display a Usage of the Item.
     * @return Response
     */
    public function getUidModel(Request $request, $id)
    {
        if( $request->ajax()):
            $html=null;
            $item = Items::where('id',$id)->first();
//            $purchase_entry_batch = \Modules\Master\Entities\PurchaseEntryBatch::with('hasOnePurchaseEntry')->with('hasOneItem')->with('hasManyBatchItems')->where('item_id',$id)->orderBy('id', 'DESC')->get();
            $html =  \View::make($this->ViewBasePath.'uid_model', compact('item'))->render();
            return response()->json(['html' => $html]);
        else:  abort(404); endif;    
    }
    
    /**
     * Display a Usage of the Item.
     * @return Response
     */
    public function getBarcodeModel(Request $request, $id)
    {
        if( $request->ajax()):
            $html=null;
            $item = Items::where('id',$id)->first();
//            $purchase_entry_batch = \Modules\Master\Entities\PurchaseEntryBatch::with('hasOnePurchaseEntry')->with('hasOneItem')->with('hasManyBatchItems')->where('item_id',$id)->orderBy('id', 'DESC')->get();
            $html =  \View::make($this->ViewBasePath.'barcod_model', compact('item'))->render();
            return response()->json(['html' => $html]);
        else:  abort(404); endif;    
    }
}
