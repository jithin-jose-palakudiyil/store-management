<?php

namespace Modules\Master\Http\Controllers\Stock;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Indents;
use Modules\Master\Repositories\CommonRepository;
use \View; use Modules\Master\Helpers\Crud;
use \Exception;

class IndentsController extends Controller
{
    public function __construct(Indents $Indent)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('stock.indents');
        $this->createUrl            =   route('stock.indents.create');  
        $this->createMessage        =   'Indent is created successfully.';
        $this->createErrorMessage   =   'Indent is not created successfully.';
        $this->updateMessage        =   'Indent is processed successfully.';
        $this->updateErrorMessage   =   'Indent is not processed successfully.';
        $this->deleteMessage        =   'Indent is deleted successfully.';
        $this->deleteErrorMessage   =   'Indent is not deleted successfully.'; 
        $this->page_title           =   'Indents';
        $this->ViewBasePath         =   'master::stock.indents.';
        $this->repository           =   new CommonRepository($Indent); 
        View::share('active', 'indents'); 
        
        $this->middleware('module_permission:indent-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:indent-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:indent-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:indent-delete', ['only' => ['destroy']]); 
         $this->middleware('module_permission:indent-transfer', ['only' => ['transfer','transfer_action']]); 
    
    }
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList(Request $request)
    {
        $indents = [];
        if($request->type=='request_sent'):
            $query = Indents::
                select('indents.*')->
                join('pivot_indent','pivot_indent.indent_id','=', 'indents.id');
                if(\Auth::guard(master_guard)->user()->role =='store'):
                  $query->where("indents.request_from",\Auth::guard(master_guard)->user()->store_id);  
                endif;
                if(\Auth::guard(master_guard)->user()->role =='master'):
                    $query->where("indents.request_from",null); 
                    $query->where("indents.from_warehouse",1); 
                endif;
        
                $indents= $query->latest('indents.created_at')->distinct();
        elseif ($request->type=='request_recived') :
            
            $query =  Indents::
                select('indents.*')->
                join('pivot_indent','pivot_indent.indent_id','=', 'indents.id');
                if(\Auth::guard(master_guard)->user()->role =='store'):
                    $query->where("indents.request_to",\Auth::guard(master_guard)->user()->store_id);
                    $query->where("indents.authority_status",1);
                endif; 
                if(\Auth::guard(master_guard)->user()->role =='master'):
//                    $query->where("indents.request_to",null); 
//                    $query->where("indents.to_warehouse",1); 
                endif;
                $indents= $query->latest('indents.created_at')->distinct();
        
        endif; 
        return \DataTables::of($indents)->make(true);   
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {  
        if(\Auth::guard(master_guard)->user()->role !='master'):
            $type= (isset($request->type)) ? $request->type :'request_sent';
        else:
            $type ='request_recived';
        endif;
        $array = [
            'breadcrumb'    =>  [ 
                [ "title" => 'Dashboard',             "url" => $this->dashboardUrl ],
                [ "title" => 'Indents',               "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','indent-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Indent Request'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','indent-edit')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'transferBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','indent-transfer')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            
            'page_title'    =>  $this->page_title,
            'type'=>$type
        ];
        if(\Auth::guard(master_guard)->user()->role =='master'):
            unset($array['CreateBtn']);
        endif; 
        return view($this->ViewBasePath.'index',$array );
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Indents $Indent)
    { 
 
        return view($this->ViewBasePath.'create', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',             "url" =>   $this->dashboardUrl ],
                [ "title" => 'Indents',               "url" =>   $this->defaultUrl, ],
                [ "title" => 'Create',                "url" =>   'javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'     =>  $this->page_title .' creating',
            'Indent'   =>  $Indent
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    { 
        $request->validate([ 'request_to' => 'required', 'item_category' => 'required|array|min:1', 'item' => 'required|array|min:1',  "item_id" => 'required|array|min:1', "qty_request" => 'required|array|min:1' ]);
        if(  ( $request->exists('request_to') && $request->exists('item_category') && $request->exists('item')    && $request->exists('item_id') && $request->exists('qty_request') )  &&   ( (count($request->item_category) == count($request->item)) && (count($request->item) == count($request->item_id))  && (count($request->item_id) == count($request->qty_request))  && (count($request->qty_request) == count($request->item_category))  ) ):
            $data['request_from'] = (\Auth::guard(master_guard)->user()->role =='store')  ? \Auth::guard(master_guard)->user()->store_id : null;
            $data['request_to'] = ($request->request_to != 'warehouse' && \Auth::guard(master_guard)->user()->role =='store')  ? $request->request_to : null;
            $data['date'] = date("d-m-Y"); 
            $data['from_warehouse'] = ($request->request_from =='WareHouse' && \Auth::guard(master_guard)->user()->role =='master')  ? 1 : 0;
            $data['to_warehouse'] = ($request->request_to =='warehouse' && \Auth::guard(master_guard)->user()->role !='master')  ? 1 : 0;
            $data['comments'] = $request->comments; $data['requested_user'] =\Auth::guard(master_guard)->user()->id;  
            $makeArraySync = $this->makeArraySync($request->all());  
            if(!$request->ajax() && !empty($makeArraySync)): 
                $Crud = Crud::store($this->repository, $data,$this->createMessage,$this->createErrorMessage);
                if($Crud['response'] !=null && $Crud['error']==null ):
                    $indent= $Crud['response'];
                    $makeArraySync = array_map(function($makeArraySync) use($indent){ return $makeArraySync + ['indent_id' => $indent->id]; }, $makeArraySync); 
                    \Modules\Master\Entities\PivotIndent::insert($makeArraySync);
                endif;
                return \Redirect::to($this->defaultUrl.'?type=request_sent');
            else: return response()->json(['message' => 'Page not found!'], 404); endif;   
        else: abort(404); endif; 
    }

    /**
     * Show the specified resource.
     * @param array $request
     * @return Renderable
     */
    public function makeArraySync($request)
    {
        $array =[];
        if (
                (  isset($request['item_category'])&& isset($request['item']) && isset($request['item_id']) && isset($request['qty_request']) ) &&
                (
                    (count($request['item_category']) == count($request['item'])) &&
                    (count($request['item']) == count($request['item_id'])) &&
                    (count($request['item_id']) == count($request['qty_request'])) 
                )
            ):
           
            foreach ($request['item_category'] as $key => $value) :
                $array[$key]['category_id'] =$value;
                $array[$key]['item_id'] =$request['item_id'][$key];
                $array[$key]['requested_quantity'] =$request['qty_request'][$key];  
            endforeach;  
        endif;
        return $array;
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request,$id)
    { 
        
        $query =  Indents::with('hasManyIndentItems')->select('indents.*')->join('pivot_indent','pivot_indent.indent_id','=', 'indents.id');
        if(\Auth::guard(master_guard)->user()->role =='store'):
            if($request->exists('type') && $request->type =='request_recived'): 
                $query->where("indents.request_to",\Auth::guard(master_guard)->user()->store_id);
            else:
                $query->where("indents.request_from",\Auth::guard(master_guard)->user()->store_id);
            endif; 
        endif; 
        $indents=$query->where("indents.id",$id)->first();  
       
        if(!$indents): abort(404); endif;
        return view($this->ViewBasePath.'show', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',             "url" =>   $this->dashboardUrl ],
                [ "title" => 'Indents',               "url" =>   $this->defaultUrl, ],
                [ "title" => 'Show',                "url" =>   'javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'     =>  $this->page_title .' show',
            'indents'   =>  $indents,'requests'=>$request
        ]);
    }
    /**
     * Edit the form resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transfer(Indents $Indent)
    {
        $Indent=$Indent->with('hasManyIndentItems')->where('id',$Indent->id)->first();
//        dd($Indent);
        //to_status ==0
        //request_to == null
        //to_warehouse == 1
        //status == 1
        $path ='';
        if(\Auth::guard(master_guard)->user()->role =='master'):
            $path ='transfer-m';
        elseif(\Auth::guard(master_guard)->user()->role =='store'):
            $path ='transfer-s';
        else:abort(404); endif;
        return view($this->ViewBasePath.$path, [
                'breadcrumb'        => [ 
                    [ "title" => 'Dashboard',               "url" =>   $this->dashboardUrl ],
                    [ "title" => 'Indents',                 "url" =>   $this->defaultUrl, ],
                    [ "title" => 'Approval',                "url" =>   'javascript:void(0)', "active" => 1 ]
                ], 
                'page_title'     =>  $this->page_title ,
                'indent'   =>  $Indent
            ]);
       
    }
    
    
    
      /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function transfer_action(Request $request, Indents $Indent){
//        dd($request->all());
        if($Indent):
            
            $error =null; 
            try{
                //has unique id
                if($request->exists('approve')):
                    foreach ($request->approve as $key => $value) :
                        $pivot_indent_id = $key; 
                        $pivot_store_ids =array_keys($value); 
                        $_PivotIndent =\Modules\Master\Entities\PivotIndent::with('hasOneItem')->where('id',$pivot_indent_id)->first(); 
                        if($_PivotIndent && isset($_PivotIndent->hasOneItem)):
                            $items = $_PivotIndent->hasOneItem; 
                            $approved_quantity = $_PivotIndent->approved_quantity;
                            $transferred_qty =  count($pivot_store_ids);  
                            $item_quantity = $items->quantity+($approved_quantity-$transferred_qty); 
                            \Modules\Master\Entities\PivotStoreItems::where('pivot_indent_id',$pivot_indent_id)->whereIn('pivot_store_items.id',$pivot_store_ids)->update(['is_transferred'=>1]); 
                            \Modules\Master\Entities\PivotStoreItems::where('pivot_indent_id',$pivot_indent_id)->whereNotIn('pivot_store_items.id',$pivot_store_ids)->update(['is_transferred'=>2]);
                            $_PivotIndent->update(['is_transferred'=>1,'transferred_qty'=>$transferred_qty]); 
                            \Modules\Master\Entities\Items::where('id',$items->id)->update(['quantity'=>$item_quantity]);
                        endif; 
                    endforeach;
                endif;

                //not has unique id
                if($request->exists('is_transferred')):
                    foreach ($request->is_transferred as $key => $value_1) :
                        $_pivot_indent_id = $key;  
                        $PivotIndent =\Modules\Master\Entities\PivotIndent::with('hasOneItem')->where('id',$_pivot_indent_id)->first();
                        if($PivotIndent && isset($PivotIndent->hasOneItem)):
                            $_items = $PivotIndent->hasOneItem; 
                            $_approved_quantity = $PivotIndent->approved_quantity;
                            $tQty = isset($request['tQty'][$_pivot_indent_id]) ? $request['tQty'][$_pivot_indent_id] : null;  
                            if($value_1 == null || $value_1 == 2):
                                $item_qty = $_items->quantity+$tQty;  
                                $PivotIndent->update(['is_transferred'=>2,'is_recived'=>2,'transferred_qty'=>0]);
                                \Modules\Master\Entities\Items::where('id',$_items->id)->update(['quantity'=>$item_qty]); 
                            elseif($value_1 == 1):     
                                $item_qty = $_items->quantity+($_approved_quantity-$tQty); 
                                $PivotIndent->update(['is_transferred'=>1,'transferred_qty'=>$tQty]);
                                \Modules\Master\Entities\Items::where('id',$_items->id)->update(['quantity'=>$item_qty]); 
                            endif; 
                        endif;
                    endforeach;
                endif; 

                $pivot_indent_ids =[]; $is_transferred_ids=[];
                if($request->exists('approve')): $pivot_indent_ids =array_keys($request->approve); endif;
                if($request->exists('is_transferred')): $is_transferred_ids =array_keys($request->is_transferred); endif;
                $combained = array_merge($pivot_indent_ids,$is_transferred_ids);
                if(!empty($combained)):  
                    $PivotIndents =\Modules\Master\Entities\PivotIndent::with('hasOneItem')->where('indent_id',$Indent->id)->whereNotIn('id',$combained)->get();
                    foreach ($PivotIndents as $key => $value_2) :
                        if(isset($value_2->hasOneItem)):
                            $approved_quantity_ = $value_2->approved_quantity;
                            $items_ = $value_2->hasOneItem; 
                            $item_qty_ = $items_->quantity+$approved_quantity_; 
                            $value_2->update(['is_transferred'=>2,'is_recived'=>2,'transferred_qty'=>0]);
                            \Modules\Master\Entities\Items::where('id',$items_->id)->update(['quantity'=>$item_qty_]); 
                        endif; 
                    endforeach; 
                endif;
                
                if(!$request->exists('approve') && !$request->exists('is_transferred')): 
                    $TPivotIndents =\Modules\Master\Entities\PivotIndent::with('hasOneItem')->where('indent_id',$Indent->id)->get();
                    foreach ($TPivotIndents as $key => $value_T) :
                        if(isset($value_T->hasOneItem)):
                            $approved_quantity_T = $value_T->approved_quantity;
                            $items_T = $value_T->hasOneItem; 
                            $item_qty_T = $items_T->quantity+$approved_quantity_T; 
                            $value_T->update(['is_transferred'=>2,'is_recived'=>2,'transferred_qty'=>0]);
                            \Modules\Master\Entities\Items::where('id',$items_T->id)->update(['quantity'=>$item_qty_T]); 
                        endif; 
                    endforeach;
                    $Indent->update(['status'=>2,'to_status'=>1,'to_user'=>\Auth::guard(master_guard)->user()->id,'from_status'=>1,'completed_from_user'=>\Auth::guard(master_guard)->user()->id]);
                else:
                    $Indent->update(['status'=>2,'to_status'=>1,'to_user'=>\Auth::guard(master_guard)->user()->id]);   
                endif;
                

            } catch (Exception $ex) { $error =  $ex->getMessage(); }
            if($error == null): 
                $request->session()->flash('flash-success-message',$this->updateMessage);
            else: 
                $request->session()->flash('flash-error-message',$this->updateErrorMessage.'<br/> '.$error);
            endif;
            return \Redirect::to(route('stock.indents').'?type=request_recived'); 
            
        else: abort(404); endif;
    }
    
      /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_transfer_action(Request $request, Indents $Indent){
        
        if($Indent):
            $error =null; 
            try{
                //has unique id
                if($request->exists('approve')):
                    foreach ($request->approve as $key => $value) :
                        $pivot_indent_id = $key; 
                        $pivot_store_ids =array_keys($value); 
                        $_PivotIndent =\Modules\Master\Entities\PivotIndent::with('hasOneItem')->where('id',$pivot_indent_id)->first(); 
                        if($_PivotIndent && isset($_PivotIndent->hasOneItem)):
                            $items = $_PivotIndent->hasOneItem; 
                            
                            $transferred_qty =  count($pivot_store_ids);  
//                            $item_quantity = $items->quantity+($approved_quantity-$transferred_qty); 
                            \Modules\Master\Entities\PivotStoreItems::where('pivot_indent_id',$pivot_indent_id)->whereIn('pivot_store_items.id',$pivot_store_ids)->update(['is_transferred'=>1]); 
                            \Modules\Master\Entities\PivotStoreItems::where('pivot_indent_id',$pivot_indent_id)->whereNotIn('pivot_store_items.id',$pivot_store_ids)->update(['is_transferred'=>2]);
                            $_PivotIndent->update(['is_transferred'=>1,'transferred_qty'=>$transferred_qty]); 
                            //\Modules\Master\Entities\Items::where('id',$items->id)->update(['quantity'=>$item_quantity]);
                            /* item selling store*/
                            $sellingStore= \Modules\Master\Entities\StoreItemsList::where('item_id',$items->id)->where('store_id',$Indent->request_to)->first();
                            if($sellingStore):
                                $approved_quantity = $_PivotIndent->approved_quantity;
                                $sellingStoreQuantity = $sellingStore->quantity +($approved_quantity-$transferred_qty);
                                $sellingStore->update(['quantity'=>$sellingStoreQuantity]);
                            endif;
                        
                        endif; 
                    endforeach;
                endif;

                //not has unique id
                if($request->exists('is_transferred')):
                    foreach ($request->is_transferred as $key => $value_1) :
                        $_pivot_indent_id = $key;  
                        $PivotIndent =\Modules\Master\Entities\PivotIndent::with('hasOneItem')->where('id',$_pivot_indent_id)->first();
                        if($PivotIndent && isset($PivotIndent->hasOneItem)):
                            $_items = $PivotIndent->hasOneItem; 
                            $_approved_quantity = $PivotIndent->approved_quantity;
                            if($value_1 == null || $value_1 == 2):
                                //$item_qty = $_items->quantity+$_approved_quantity;  
                                $PivotIndent->update(['is_transferred'=>2,'is_recived'=>2,'transferred_qty'=>0]);
                                /* item selling store*/
                                $_sellingStore= \Modules\Master\Entities\StoreItemsList::where('item_id',$_items->id)->where('store_id',$Indent->request_to)->first();
                                if($_sellingStore): 
                                    $_sellingStoreQuantity = $_sellingStore->quantity +$_approved_quantity;
                                    $_sellingStore->update(['quantity'=>$_sellingStoreQuantity]);
                                endif;
                                //\Modules\Master\Entities\Items::where('id',$_items->id)->update(['quantity'=>$item_qty]); 
                            elseif($value_1 == 1):     
                                $PivotIndent->update(['is_transferred'=>1,'transferred_qty'=>$_approved_quantity]);
                            endif; 
                        endif;
                    endforeach;
                endif; 

                $pivot_indent_ids =[]; $is_transferred_ids=[];
                if($request->exists('approve')): $pivot_indent_ids =array_keys($request->approve); endif;
                
                if($request->exists('is_transferred')): $is_transferred_ids =array_keys($request->is_transferred); endif;
                
                $combained = array_merge($pivot_indent_ids,$is_transferred_ids);
                if(!empty($combained)): 
                    $PivotIndents =\Modules\Master\Entities\PivotIndent::with('hasOneItem')->where('indent_id',$Indent->id)->whereNotIn('id',$combained)->get();
                    foreach ($PivotIndents as $key => $value_2) :
                        if(isset($value_2->hasOneItem)):
                            $approved_quantity_ = $value_2->approved_quantity;
                            $items_ = $value_2->hasOneItem; 
                            //$item_qty_ = $items_->quantity+$approved_quantity_; 
                            $value_2->update(['is_transferred'=>2,'is_recived'=>2,'transferred_qty'=>0]);
                            //\Modules\Master\Entities\Items::where('id',$items_->id)->update(['quantity'=>$item_qty_]); 
                            /* item selling store*/
                            $sellingStore_= \Modules\Master\Entities\StoreItemsList::where('item_id',$items_->id)->where('store_id',$Indent->request_to)->first();
                            if($sellingStore_): 
                                $sellingStore_Quantity = $sellingStore_->quantity +$approved_quantity_;
                                $sellingStore_->update(['quantity'=>$sellingStore_Quantity]);
                            endif;
                                
                        endif; 
                    endforeach; 
                endif;
                
                
                if(!$request->exists('approve') && !$request->exists('is_transferred')): 
                    $TPivotIndents =\Modules\Master\Entities\PivotIndent::with('hasOneItem')->where('indent_id',$Indent->id)->get();
                    foreach ($TPivotIndents as $key => $value_T) :
                        if(isset($value_T->hasOneItem)):
                            $approved_quantity_T = $value_T->approved_quantity;
                            $items_T = $value_T->hasOneItem; 
                            $item_qty_T = $items_T->quantity+$approved_quantity_T; 
                            $value_T->update(['is_transferred'=>2,'is_recived'=>2,'transferred_qty'=>0]);
//                            \Modules\Master\Entities\Items::where('id',$items_T->id)->update(['quantity'=>$item_qty_T]); 
                            $sellingStore_T= \Modules\Master\Entities\StoreItemsList::where('item_id',$items_->id)->where('store_id',$Indent->request_to)->first();
                            if($sellingStore_T): 
                                $sellingStore_Quantity_T = $sellingStore_T->quantity +$approved_quantity_T;
                                $sellingStore_T->update(['quantity'=>$sellingStore_Quantity_T]);
                            endif;
                        endif; 
                    endforeach;
                    $Indent->update(['status'=>2,'to_status'=>1,'to_user'=>\Auth::guard(master_guard)->user()->id,'from_status'=>1,'completed_from_user'=>\Auth::guard(master_guard)->user()->id]);
                else:
                    $Indent->update(['status'=>2,'to_status'=>1,'to_user'=>\Auth::guard(master_guard)->user()->id]);   
                endif;
                
            } catch (Exception $ex) { $error =  $ex->getMessage(); }
            if($error == null): 
                $request->session()->flash('flash-success-message',$this->updateMessage);
            else: 
                $request->session()->flash('flash-error-message',$this->updateErrorMessage.'<br/> '.$error);
            endif;
            return \Redirect::to(route('stock.indents').'?type=request_recived'); 
            
        else: abort(404); endif;
    }
    
    
    /**
     * Edit the form resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Indents $Indent)
    {
        
        $Indent=$Indent->with('hasManyIndentItems')->where('id',$Indent->id)->first();
        if(\Auth::guard(master_guard)->user()->role =='master'):
            return view($this->ViewBasePath.'edit-m', [
                'breadcrumb'        => [ 
                    [ "title" => 'Dashboard',               "url" =>   $this->dashboardUrl ],
                    [ "title" => 'Indents',                 "url" =>   $this->defaultUrl, ],
                    [ "title" => 'Approval',                "url" =>   'javascript:void(0)', "active" => 1 ]
                ], 
                'page_title'     =>  $this->page_title ,
                'Indent'   =>  $Indent
            ]);
        else:abort(404); endif;
       
    }
    
     /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Indents $Indent)
    {
//        dd($request->all());
       
         
        $request->validate([ '_id' => 'required|array|min:1', 'approved_qty' => 'required|array|min:1', "status" => 'required|array|min:1']);
        if  ( (count($request->_id)==count($request->approved_qty)) && (count($request->approved_qty)==count($request->status)) && (count($request->status)==count($request->_id)) ):
            $error= null; 
            try{
                foreach ($request->_id as $key => $value):
                    //check Indent is requested to the item
                    $PivotIndent = \Modules\Master\Entities\PivotIndent::with('hasOneItem')->where('id',$value)->where('indent_id',$Indent->id)->first();
                    if($PivotIndent && isset($PivotIndent->hasOneItem->id)):
                        $_item = $PivotIndent->hasOneItem; 
                        $item_quantity = $_item->quantity; 
                        $StoreItemsList = \Modules\Master\Entities\StoreItemsList::where('item_id',$_item->id)->where('store_id',$Indent->request_from)->first();
                        // check item already assigned or not to the store
                        if(!$StoreItemsList):  
                            $StoreItemsList = \Modules\Master\Entities\StoreItemsList::create([ 'quantity'=>0,'item_id'=>$_item->id,'store_id'=>$Indent->request_from  ]);
                        endif;
                        //check item has unique id or not
                        if($_item->has_unique_id==1 && isset($request['batch_item'][$key]) && !empty($request['batch_item'][$key]) && isset($request['status'][$key]) && $request['status'][$key]==1):
                            $batch_item = $request['batch_item'][$key];
                            $batch_array =[];
                            foreach ($batch_item as $key_1 => $value_1) :
                                 
                                $check= \Modules\Master\Entities\PivotStoreItems::where('batch_item_id',$value_1)->where('store_item_id',$StoreItemsList->id)->where('pivot_indent_id',$PivotIndent->id)->first();
                                if(!$check): 
                                    $batch_array[$key_1]['batch_item_id'] =$value_1;
                                    $batch_array[$key_1]['store_item_id'] =$StoreItemsList->id;
                                    $batch_array[$key_1]['pivot_indent_id'] =$PivotIndent->id;
//                                    $batch_array[$key_1]['is_requested'] =1;
                                endif;
                            endforeach;
                            if(!empty($batch_array)):   \Modules\Master\Entities\PivotStoreItems::insert($batch_array); endif; 
                            $item_quantity = $_item->quantity-count($request['batch_item'][$key]);
                        else:
                            /* not have unique id */
                            $item_quantity = $_item->quantity-$request['approved_qty'][$key];
                        endif; 
                     
                        //update item quantity
                        if($request['status'][$key]==1): $_item->update(['quantity'=>$item_quantity]); endif;
                         
                        //update Pivot Indent
                        $PivotIndent->update(['approved_quantity'=>$request['approved_qty'][$key],'status'=>$request['status'][$key]]);
                    endif;
                endforeach;
                
                
            } catch (Exception $ex) { $error = $ex->getMessage(); } 
            if($error == null): 
                $ar = ['status'=>1,'authority_status'=>1,'authority_user'=>\Auth::guard(master_guard)->user()->id];
//                if($Indent->to_warehouse==1 && $Indent->request_to==null ): $ar['to_status']=1; endif;
                $Indent->update($ar);
                $request->session()->flash('flash-success-message',$this->updateMessage);
            else: 
                $request->session()->flash('flash-error-message',$this->updateErrorMessage.'<br/> '.$error);
            endif;
            return \Redirect::to(route('stock.indents').'?type=request_recived'); 
        else: abort(404); endif;
        
        
        
        
        
        
        
        
    }
    
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Object $Indent
     * @return Renderable
     */
    public function store_action(Request $request, Indents $Indent)
    {
        
//        dd('store_action');
        $request->validate([ '_id' => 'required|array|min:1', 'approved_qty' => 'required|array|min:1', "status" => 'required|array|min:1']);
        if  ( (count($request->_id)==count($request->approved_qty)) && (count($request->approved_qty)==count($request->status)) && (count($request->status)==count($request->_id)) ):
            $error= null; 
            try{
                
                foreach ($request->_id as $key => $value):
                    //check Indent is requested to the item
                    $PivotIndent = \Modules\Master\Entities\PivotIndent::with('hasOneItem')->where('id',$value)->where('indent_id',$Indent->id)->first();
                    $_quantity =0; 
                    if($PivotIndent && isset($PivotIndent->hasOneItem->id)):
                        $_item = $PivotIndent->hasOneItem;
                        //check the store has the item
                        $StoreItemsList = \Modules\Master\Entities\StoreItemsList::where('item_id',$_item->id)->where('store_id',$Indent->request_from)->first();
                        if(!$StoreItemsList):  
                            $StoreItemsList = \Modules\Master\Entities\StoreItemsList::create([ 'quantity'=>0,'item_id'=>$_item->id,'store_id'=>$Indent->request_from  ]);
                        endif;
                        
                        //check item has unique id or not
                        if($_item->has_unique_id==1 && isset($request['batch_item'][$key]) && !empty($request['batch_item'][$key]) && isset($request['status'][$key]) && $request['status'][$key]==1):
                            $batch_item = $request['batch_item'][$key];
                            $batch_array =[];
                            foreach ($batch_item as $key_1 => $value_1) :
                                 //check the pivot store has assigned the items
                                $check= \Modules\Master\Entities\PivotStoreItems::where('batch_item_id',$value_1)->where('store_item_id',$StoreItemsList->id)->where('pivot_indent_id',$PivotIndent->id)->first();
                                if(!$check): 
                                    $batch_array[$key_1]['batch_item_id'] =$value_1;
                                    $batch_array[$key_1]['store_item_id'] =$StoreItemsList->id;
                                    $batch_array[$key_1]['pivot_indent_id'] =$PivotIndent->id; 
                                endif;
                            endforeach;
                            if(!empty($batch_array)):   \Modules\Master\Entities\PivotStoreItems::insert($batch_array); endif; 
                            $_quantity = count($request['batch_item'][$key]);
                        else:
                            /* not have unique id */
                            $_quantity = $request['approved_qty'][$key];
                        endif;
                        
                        /* item moving*/
                        $moveMoving= \Modules\Master\Entities\StoreItemsList::where('item_id',$_item->id)->where('store_id',$Indent->request_to)->first();
                        if($moveMoving):
                            $moveMovingQuantity = $moveMoving->quantity -$_quantity;
                            $moveMoving->update(['quantity'=>$moveMovingQuantity]);
                        endif;
                        
                        /*item reciving */
//                        $moveReceiving = \Modules\Master\Entities\StoreItemsList::where('item_id',$_item->id)->where('store_id',$Indent->request_from)->first();
//                        if($moveReceiving):
//                            $moveReceivingQuantity = $moveReceiving->quantity +$_quantity;
//                            $moveReceiving->update(['quantity'=>$moveReceivingQuantity]);
//                        endif;
                         
                        //update Pivot Indent
                        $PivotIndent->update(['approved_quantity'=>$request['approved_qty'][$key],'status'=>$request['status'][$key]]);
                    endif;
                endforeach;
                
            } catch (Exception $ex) { $error = $ex->getMessage(); } 
            if($error == null): 
                $ar = ['status'=>1,'authority_status'=>1,'authority_user'=>\Auth::guard(master_guard)->user()->id];
                $Indent->update($ar);
                $request->session()->flash('flash-success-message',$this->updateMessage);
            else: 
                $request->session()->flash('flash-error-message',$this->updateErrorMessage.'<br/> '.$error);
            endif;
            return \Redirect::to(route('stock.indents').'?type=request_recived'); 
        else: abort(404); endif;
        
    }
    
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ItemAutocomplete(Request $request)
    {
        $res = []; 
        if($request->exists('request_to') && $request->request_to=='warehouse'):
            $res = \Modules\Master\Entities\Items::with('hasOneMeasurements:id,short_code') 
                ->where("name","LIKE","%{$request->term}%")
                ->where("category_id",$request->item_category) 
                ->where("status",1)
                ->get();
        elseif($request->exists('request_to') && $request->request_to != 'warehouse'):
                $store = \Modules\Master\Entities\Store::find($request->request_to);
                if($store):
                    $res = \Modules\Master\Entities\Items::select('items.id','items.has_unique_id','items.name','items.status','items.category_id','items.measurement_id','store_items_list.id as store_id','store_items_list.quantity AS quantity')
//                        \DB::raw('( CASE WHEN items.has_unique_id !=1 THEN  store_items_list.quantity  WHEN items.has_unique_id =1  THEN  ( SELECT COUNT(*) FROM pivot_store_items WHERE pivot_store_items.store_item_id IN (SELECT id FROM store_items_list WHERE store_items_list.store_id = '.$request->request_to.') )    ELSE 0 END) AS quantity') )
                         ->with('hasOneMeasurements:id,short_code')
                        ->with('hasOneItemCategory:id,allow_usage,name')
                        ->join('store_items_list','store_items_list.item_id','=', 'items.id') 
                        ->where('store_items_list.store_id',$request->request_to)
                        ->where("items.category_id",$request->item_category)
                        ->where("items.name","LIKE","%{$request->term}%")
                        ->latest('items.created_at')->get();
                     
                endif;
        endif;
        return response()->json($res);
    }
    
     /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_update(Request $request, Indents $Indent)
    {
        $request->validate([ 'is_transferred' => 'required|array|min:1' ]);
        $error = null;
        try{
            foreach ($request->is_transferred as $key => $value) : 
                $PivotIndent = \Modules\Master\Entities\PivotIndent::find($key);
                $PivotIndent->update(['is_transferred'=>$value]);
            endforeach;
        } catch (Exception $ex) { $error = $ex->getMessage(); }
        
        if($error == null): 
            $ar = ['to_status'=>1,'to_user'=>\Auth::guard(master_guard)->user()->id];
            $Indent->update($ar);
            $request->session()->flash('flash-success-message','Transfer completed');
        else: 
            $request->session()->flash('flash-error-message','Transfer not completed'.'<br/> '.$error);
        endif;
        return \Redirect::to(route('stock.indents').'?type=request_recived');
    }
    
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_from_update(Request $request, Indents $Indent)
    {
//        dd('s');
         
         
        $error = null;
        try {
            
            /*has unique id */
            if($request->exists('pivot_store_id')):
                foreach ($request->pivot_store_id as $_key => $_value): 
                    $_pivotIndent = \Modules\Master\Entities\PivotIndent::where('indent_id',$Indent->id)->where('id',$_key)->with('hasOneItem')->first();
             
                    if($_pivotIndent && isset($_pivotIndent->hasOneItem)):
                        $_item = $_pivotIndent->hasOneItem;  
                        $recived_qty = count($_value); 
                        \Modules\Master\Entities\PivotStoreItems::where('pivot_indent_id',$_pivotIndent->id)->whereIn('pivot_store_items.id',$_value)->update(['is_recived'=>1]);
                        \Modules\Master\Entities\PivotStoreItems::where('pivot_indent_id',$_pivotIndent->id)->whereNotIn('pivot_store_items.id',$_value)->update(['is_recived'=>2]);
                        $_pivotIndent->update(['recived_qty'=>$recived_qty]); 
                        
                        if($Indent->request_to==null && $Indent->to_warehouse==1):
                            /*Store To warehouse*/
                            
                            $_itemQty=$_item->quantity+($_pivotIndent->transferred_qty-$recived_qty);  
                            \Modules\Master\Entities\Items::where('id',$_item->id)->update(['quantity'=>$_itemQty]); 
                             
                         elseif($Indent->request_to != null && $Indent->to_warehouse != 1):
                            /*Store To Store*/   
//                            dd('Store To Store uniq'); 
                             /*item reciving */
                            $moveReceiving = \Modules\Master\Entities\StoreItemsList::where('item_id',$_item->id)->where('store_id',$Indent->request_to)->first();
                            if($moveReceiving):
                                $moveReceivingQuantity=$moveReceiving->quantity+($_pivotIndent->transferred_qty-$recived_qty);  
                                $moveReceiving->update(['quantity'=>$moveReceivingQuantity]);
                            endif;
                             
                        endif;  
                        /* update store quantity */
                        $StoreItemsList = \Modules\Master\Entities\StoreItemsList::where('item_id',$_pivotIndent->item_id)->where('store_id',$Indent->request_from)->first();
                        if($StoreItemsList):
                            $StoreItemsList->update(['quantity'=>$StoreItemsList->quantity+$recived_qty]);
                        endif;
                    endif;
                endforeach;
                
            else:
                
                $IndentPivot = \Modules\Master\Entities\PivotIndent::where('indent_id',$Indent->id)->with(['hasOneItem' => function ($query) {$query->select('*');$query->where('has_unique_id', 1);}])->get();
                $IndentPivotId =$IndentPivot->pluck('id')->toArray();
                 
                \Modules\Master\Entities\PivotStoreItems::whereIn('pivot_indent_id',$IndentPivotId)->update(['is_recived'=>2]);
                foreach ($IndentPivot as $key_1 => $value_1): 
                    if(isset($value_1->hasOneItem) && $value_1->hasOneItem != null):  
                        $value_1->update(['recived_qty'=>0]);
                        $item_1 = $value_1->hasOneItem;  
                        if($Indent->request_to==null && $Indent->to_warehouse==1):
                            $itemQty_1=$item_1->quantity+$value_1->transferred_qty;  
                            \Modules\Master\Entities\Items::where('id',$item_1->id)->update(['quantity'=>$itemQty_1]);
                            
                        elseif($Indent->request_to!=null && $Indent->to_warehouse!=1):
                            
                            /* update store quantity */
                            $StoreItemsList = \Modules\Master\Entities\StoreItemsList::where('item_id',$value_1->item_id)->where('store_id',$Indent->request_to)->first();
                            if($StoreItemsList):
                                $StoreItemsList->update(['quantity'=>$StoreItemsList->quantity+$value_1->transferred_qty]);
                            endif;
                        endif; 
                    endif; 
                endforeach;      
            endif;
            
              
            /*not has unique id */
            if($request->exists('is_recived')):
                
                foreach ($request->is_recived as $key => $value): 
                    $pivotIndent = \Modules\Master\Entities\PivotIndent::where('indent_id',$Indent->id)->where('id',$key)->with('hasOneItem')->first();
                    if($pivotIndent && isset($pivotIndent->hasOneItem)):
                        $_is_recived_chk =($value!='') ? $value : 2;
                        $pivotIndent->update(['is_recived'=>$_is_recived_chk]);  
                        $_recived_qty = $pivotIndent->transferred_qty; 
                        $item = $pivotIndent->hasOneItem;  
                        if($Indent->request_to==null && $Indent->to_warehouse==1):
                            /*Store To warehouse*/
                           
                            if($value==2): /* Not Recived/Restore Qty to WareHouse*/
                                $_recived_qty =0;
                                \Modules\Master\Entities\Items::where('id',$item->id)->update(['quantity'=>$item->quantity+$pivotIndent->transferred_qty]); 
                                 
                            elseif($value==1):
                                $pivotIndent->update(['recived_qty'=>$pivotIndent->transferred_qty]);
                            endif;
                        elseif($Indent->request_to != null && $Indent->to_warehouse != 1):
                            /*Store To Store*/   
//                            dd('Store To Store not uniq');
                            /*item reciving */
                            if($value==2):
                                $_recived_qty =0;
                                $_moveReceiving = \Modules\Master\Entities\StoreItemsList::where('item_id',$item->id)->where('store_id',$Indent->request_to)->first();
                                if($_moveReceiving):
                                    $_moveReceivingQuantity=$_moveReceiving->quantity+$pivotIndent->transferred_qty;  
                                    $_moveReceiving->update(['quantity'=>$_moveReceivingQuantity]);
                                endif;
                            elseif($value==1):
                                $pivotIndent->update(['recived_qty'=>$pivotIndent->transferred_qty]);
                            endif;
                            
                        endif; 
                        /* update store quantity */
                        $StoreItemsList = \Modules\Master\Entities\StoreItemsList::where('item_id',$pivotIndent->item_id)->where('store_id',$Indent->request_from)->first();
                        if($StoreItemsList):
                            $StoreItemsList->update(['quantity'=>$StoreItemsList->quantity+$_recived_qty]);
                        endif;
                    endif;
                endforeach;
            endif;
            /* /not has unique id */
            
            
      
            
            
            
            
            
            
            
            
            
            
            
            
            
            
          } catch (Exception $ex) { $error = $ex->getMessage(); } 
        
        if($error == null): 
            $ar = ['from_status'=>1,'completed_from_user'=>\Auth::guard(master_guard)->user()->id];
            $Indent->update($ar);
            $request->session()->flash('flash-success-message','Receiving completed');
        else: 
            $request->session()->flash('flash-error-message','Receiving not completed'.'<br/> '.$error);
        endif;
        return \Redirect::to(route('stock.indents').'?type=request_sent');
    
            
            
//            if($request->exists('is_recived')):
//                foreach ($request->is_recived as $key => $value):
//                 $pivotIndent = \Modules\Master\Entities\PivotIndent::where('indent_id',$Indent->id)
//                    ->where('id',$key)->with('hasOneItem')->first(); 
//                    $pivotIndent->update(['is_recived'=>$value]); 
//                    $_item = $pivotIndent->hasOneItem;
//    //                $transferred_qty= $pivotIndent->transferred_qty;
//                    if($Indent->request_to==null && $Indent->to_warehouse==1):
//                        //WereHouse to store
//                        if($value==2):
//                            $_item->update(['quantity'=>($_item->quantity+$pivotIndent->transferred_qty)]); 
//                        endif;
//                    elseif($Indent->request_to != null && $Indent->to_warehouse != 1):    
//                        //store to store
//                        dd('store to store');
//                    endif; 
//                    
////                    if($_item->has_unique_id==1):
////                        //delete the id
////                        $PivotStoreItems = \Modules\Master\Entities\PivotStoreItems::where('pivot_indent_id',$pivotIndent->id)->get();
////                        $batch_item_id = $PivotStoreItems->pluck('batch_item_id')->toArray(); 
////                        \Modules\Master\Entities\PivotStoreItems::
////                                join('store_items_list', 'store_items_list.id', '=', 'pivot_store_items.store_item_id')
////                                ->where('store_items_list.item_id',$pivotIndent->item_id)
////                                ->where('store_items_list.store_id',$Indent->request_to)
////                                ->whereIn('pivot_store_items.batch_item_id',$batch_item_id)
////                                ->delete();
////                    endif;
//                endforeach;
//
//            endif;
        
//        dd($request->all());
//        $request->validate([ 'is_recived' => 'required|array|min:1' ]);
//        
//        $error = null;
//        try{
//            foreach ($request->is_recived as $key => $value) : 
//                if($value==1):
//                    $PivotIndent = \Modules\Master\Entities\PivotIndent::where('id',$key)->with('hasOneItem')->first();
//                    if(isset($PivotIndent->hasOneItem->id)):
//                        //get item
//                        $_item = $PivotIndent->hasOneItem;
//                        $move_from = \Modules\Master\Entities\StoreItemsList::where('item_id',$_item->id)->where('store_id',$Indent->request_to)->first();
//                        $move_to = \Modules\Master\Entities\StoreItemsList::where('item_id',$_item->id)->where('store_id',$Indent->request_from)->first();
//                        
//                        if($move_from && $move_from) : 
//                            $approved_quantity = $PivotIndent->approved_quantity; 
//                         
//                            $move_from->update([ 'quantity'=>($move_from->quantity-$approved_quantity) ]); 
//                            $move_to->update(['quantity'=> ($move_to->quantity+$approved_quantity) ]);
//                            
//                            
//                            if($_item->has_unique_id==1):
//                                //delete the id
//                                $PivotStoreItems = \Modules\Master\Entities\PivotStoreItems::where('pivot_indent_id',$PivotIndent->id)->get();
//                                $batch_item_id = $PivotStoreItems->pluck('batch_item_id')->toArray(); 
//                                \Modules\Master\Entities\PivotStoreItems::
//                                        join('store_items_list', 'store_items_list.id', '=', 'pivot_store_items.store_item_id')
//                                        ->where('store_items_list.item_id',$PivotIndent->item_id)
//                                        ->where('store_items_list.store_id',$Indent->request_to)
//                                        ->whereIn('pivot_store_items.batch_item_id',$batch_item_id)
//                                        ->delete();
//                                //update the rows
//                                \Modules\Master\Entities\PivotStoreItems::
//                                        join('store_items_list', 'store_items_list.id', '=', 'pivot_store_items.store_item_id')
//                                        ->where('store_items_list.item_id',$PivotIndent->item_id)
//                                        ->where('store_items_list.store_id',$Indent->request_from)
//                                        ->whereIn('pivot_store_items.batch_item_id',$batch_item_id)->update(['is_requested'=>0]); 
//                            endif;
//                        endif;
//                    endif; 
//                endif; 
//                $PivotIndent->update(['is_recived'=>$value]);
//            endforeach;
            
              
        
    }
    
    
}
