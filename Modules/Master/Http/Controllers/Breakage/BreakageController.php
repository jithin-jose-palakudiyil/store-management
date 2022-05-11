<?php

namespace Modules\Master\Http\Controllers\Breakage;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Breakage;
use Modules\Master\Repositories\CommonRepository;
use \View; use \Modules\Master\Helpers\Crud;
use \Exception;
class BreakageController extends Controller
{
    protected $repository;
    public function __construct(Breakage $Breakage)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('breakage');
        $this->createUrl            =   route('breakage.create');  
        $this->createUrlMaster      =   route('breakage-m.create');  
        $this->createMessage        =   'Breakage is created successfully.';
        $this->createErrorMessage   =   'Breakage is not created successfully.';
        $this->updateMessage        =   'Breakage is updated successfully.';
        $this->updateErrorMessage   =   'Breakage is not updated successfully.';
        $this->deleteMessage        =   'Breakage is deleted successfully.';
        $this->deleteErrorMessage   =   'Breakage is not deleted successfully.';  
        $this->repository           =   new CommonRepository($Breakage); 
        $this->page_title           =   'Breakage / Breakdown';
        $this->ViewBasePath         =   'master::breakage.';
        View::share('active', 'breakage');
        
        $this->middleware('module_permission:breakage-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:breakage-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:breakage-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:breakage-delete', ['only' => ['destroy']]); 
    
    }
    
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList(Request $request)
    {
         
//        $item = \Modules\Master\Entities\PivotStoreItems::select('store_items_list.store_id','items.id as item_id','batch_items.unique_id','pivot_store_items.id as pivot_id')->join('store_items_list','store_items_list.id','=', 'pivot_store_items.store_item_id') ->join('items','items.id','=', 'store_items_list.item_id') ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id') ->join('purchase_entry_batch','purchase_entry_batch.id','=', 'batch_items.batch_id') ->where('store_items_list.store_id',\Auth::guard(master_guard)->user()->store_id)->where('pivot_store_items.is_recived',1)->where('pivot_store_items.is_breakage',0)->where('pivot_store_items.id',$request->pivot_id)->where('items.has_unique_id',1)->first();
            $query = Breakage::select('breakage.is_status','breakage.id','breakage.step','breakage.breakage_date','items.name','batch_items.unique_id','breakage.is_permanently')
                    ->join('items','items.id','=', 'breakage.item_id')
                    ->join('pivot_store_items','pivot_store_items.id','=', 'breakage.pivot_store_item_id')
                    ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id');
                if(\Auth::guard(master_guard)->user()->role !='master' ):
                     $query->where('store_id',\Auth::guard(master_guard)->user()->store_id);
                endif; 
              
            $breakage = $query->latest('breakage.created_at');
          
        return \DataTables::of($breakage)->make(true);   
    }
    
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $array = 
        [
            'breadcrumb'    =>  [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Breakage / Breakdown',    "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'page_title'    =>  $this->page_title,
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','breakage-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) && \Auth::guard(master_guard)->user()->role !='master' ) ? ['url'=>$this->createUrl,'btn_txt'=>'Breakage / Breakdown'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','breakage-edit')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','breakage-delete')->first() || \Auth::guard(master_guard)->user()->is_developer==1) && \Auth::guard(master_guard)->user()->role =='master' ) ? true : false,
            'gatePassBtn'   => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','gate-pass-list')->first() || \Auth::guard(master_guard)->user()->is_developer==1) || \Auth::guard(master_guard)->user()->role =='master' ) ? true : false,
        ];
        
//        if( (\Auth::guard(master_guard)->user()->role =='store' && \Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','breakage-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) && \Auth::guard(master_guard)->user()->role =='store' ):
////            $array['CreateBtn']=['url'=>$this->createUrl,'btn_txt'=>'Breakage / Breakdown'];
//        elseif( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','breakage-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) && \Auth::guard(master_guard)->user()->role =='master' ):
//            //$array['CreateBtn']=['url'=>$this->createUrlMaster,'btn_txt'=>'Breakage / Breakdown'];
//        endif;
        return view($this->ViewBasePath.'index',$array );
        
       
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Breakage $Breakage)
    {
        if(\Auth::guard(master_guard)->user()->role !='master'): 
         return view($this->ViewBasePath.'create', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
                [ "title" => 'Breakage / Breakdown',         "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',           "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' creating',
            'Breakage'   =>  $Breakage
        ]); 
        else: abort(404); endif;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
      
        $request->validate([
                'unique_id' => 'required', 
                'what_is' => 'required', 
                'pivot_id' => 'required|numeric',
                'is_responsible' => 'required|numeric',
                'breakage_date' => 'required',
                'price' => 'required|numeric', 
                'name' => 'required|array|min:1', 
                '_id' => 'required|array|min:1',
                "contact_number" => 'required|array|min:1',
                "batch" => 'required|array|min:1',
                "class" => 'required|array|min:1'
            ]);
        
        if(!$request->ajax()): 
            $item = \Modules\Master\Entities\PivotStoreItems::select('pivot_store_items.id as pivot_store_id','store_items_list.store_id','items.id as item_id','batch_items.unique_id','pivot_store_items.id as pivot_id')->join('store_items_list','store_items_list.id','=', 'pivot_store_items.store_item_id') ->join('items','items.id','=', 'store_items_list.item_id') ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id') ->join('purchase_entry_batch','purchase_entry_batch.id','=', 'batch_items.batch_id') ->where('store_items_list.store_id',\Auth::guard(master_guard)->user()->store_id)->where('pivot_store_items.is_recived',1)->where('pivot_store_items.is_breakage',0)->where('pivot_store_items.id',$request->pivot_id)->where('items.has_unique_id',1)->first();
            
        if($item): 
               
                $StoreItemsList =\Modules\Master\Entities\StoreItemsList::where('item_id',$item->item_id)->where('store_id',\Auth::guard(master_guard)->user()->store_id)->first();
                
                if($StoreItemsList): 
                    
                    $qty = $StoreItemsList->quantity;
                    $data =[ 'what_is'=>$request->what_is,'item_id'=> $item->item_id, 'pivot_store_item_id'=> $item->pivot_id, 'store_id' => $item->store_id, 'is_responsible' => $request->is_responsible, 'breakage_date' => $request->breakage_date, 'price' => $request->price, 'comments' => $request->exists('comments')  ? $request->comments : null, ]; 
                    $pivot =[];
                    if (
                            ( count($request['name']) == count($request['_id']) ) &&
                            ( count($request['_id']) == count($request['contact_number']) ) &&
                            ( count($request['contact_number']) == count($request['name']) ) 
                        ):
                            $i=0;    
                            foreach ($request['name'] as $key => $value) :
                                $pivot[$i]['student_id'] = (  $request->is_responsible==0 && isset($request['_id'][$key]) ) ? $request['_id'][$key] : null;
                                $pivot[$i]['employee_id'] =( $request->is_responsible==1 && isset($request['_id'][$key]) ) ? $request['_id'][$key] : null;
                                $pivot[$i]['name'] =$value;
                                $pivot[$i]['contact_number'] =( isset($request['contact_number'][$key]) ) ? $request['contact_number'][$key] : null;
                                $pivot[$i]['batch'] =( isset($request['batch'][$key]) ) ? $request['batch'][$key] : null;
                                $pivot[$i]['class'] =( isset($request['class'][$key]) ) ? $request['class'][$key] : null;
                            $i++;
                            endforeach; 
                    endif; 
                    $Crud = Crud::store($this->repository, $data,$this->createMessage,$this->createErrorMessage);
                    if($Crud['response'] !=null && $Crud['error']==null ):
                        $breakage = $Crud['response'];
                        $pivot = array_map(function($pivot) use($breakage){
                            return $pivot + ['breakage_id' => $breakage->id];  
                        }, $pivot); 
                        if(!empty($pivot)):
                         \Modules\Master\Entities\PivotBreakage::insert($pivot);
                        endif;
                    endif;  
                    \Modules\Master\Entities\PivotStoreItems::where('id',$item->pivot_store_id)->update(['is_breakage'=>1]);
                    $StoreItemsList->update(['quantity'=>$qty-1]);
                    return \Redirect::to($this->defaultUrl);
                else: return abort(404);  endif;
            else: abort(404); endif;   
            
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif; 
        
     
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Breakage $Breakage)
    {
         
        
         return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Breakage / Breakdown',            "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'Breakage'      =>  $Breakage
        ]); 
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Breakage $Breakage)
    { 
//        dd($request->all());
         
        if(\Auth::guard(master_guard)->user()->role =='master' ):
            
            if( isset($Breakage['step']) && $Breakage['step'] ==4 ):
                $request->validate([  "is_permanently"     =>  "required",   ],['is_permanently.required'=>'The Action of authority for permanently damaged field is required.']);
                $data=['is_permanently'=>$request->is_permanently];
            else:
                $request->validate([  "status"     =>  "required|numeric",   ]);
                $data=['status'=>$request->status,'step'=>1];
            endif;
        else:
            $request->validate([  "step"     =>  "required|numeric",   ]);
            $data=['step'=>$request->step];
        endif;
        
        if($request->step!=4 && \Auth::guard(master_guard)->user()->role =='store'):
            $data['is_status']=1;
        endif;
        
        if( $request->exists('is_permanently') && isset($Breakage['step']) && $Breakage['step'] ==4 && \Auth::guard(master_guard)->user()->role =='master'): 
            $data['is_status']=1;
        endif;
        
        if(!$request->ajax()):
            $StoreItemsList =\Modules\Master\Entities\StoreItemsList::where('item_id',$Breakage->item_id)->where('store_id',$Breakage->store_id)->first();
            if($StoreItemsList):
                $qty = $StoreItemsList->quantity; 
                $Crud =  Crud::update($this->repository, $Breakage,$data,$this->updateMessage,$this->updateErrorMessage);
                if($Crud['error']==null && $Crud['response']):
                    if($request->step==2): 
                        \Modules\Master\Entities\PivotStoreItems::where('id',$Breakage->pivot_store_item_id)->update(['is_breakage'=>0]);
                        $StoreItemsList->update(['quantity'=>$qty+1]);
                    endif;
                    if( $request->exists('is_permanently') && isset($Breakage['step']) && $Breakage['step'] ==4 && \Auth::guard(master_guard)->user()->role =='master'): 
                        if($request->is_permanently==2):     
                            \Modules\Master\Entities\PivotStoreItems::where('id',$Breakage->pivot_store_item_id)->update(['is_breakage'=>0]);
                            $StoreItemsList->update(['quantity'=>$qty+1]);
                        elseif($request->is_permanently==1): 
                            \Modules\Master\Entities\PivotStoreItems::where('id',$Breakage->pivot_store_item_id)->update(['deleted_at'=> \Carbon\Carbon::now()]);
                        endif;
                    endif;
                endif; 
                return \Redirect::to($this->defaultUrl );
            
            else: abort(404); endif;  
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif; 
        
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Breakage $Breakage)
    {
        if(\Auth::guard(master_guard)->user()->role =='master' ):
        return Crud::destroy(
            $this->repository, $Breakage,$this->deleteMessage,$this->deleteErrorMessage
        );
       else:abort(404);  endif;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ItemAutocomplete(Request $request)
    {
        if(\Auth::guard(master_guard)->user()->role =='master'): 
            $res = \Modules\Master\Entities\Items::
                select('items.*','batch_items.unique_id','batch_items.id as batch_item_id','purchase_entry_batch.amount') 
//                select("items.name","batch_items.id","batch_items.unique_id")
                ->join('batch_items','batch_items.item_id','=', 'items.id')  
                ->join('purchase_entry_batch','purchase_entry_batch.id','=', 'batch_items.batch_id')
                ->where("batch_items.whs_breakage",0)
                ->where("batch_items.unique_id","LIKE","%{$request->term}%")
                ->where("items.status",1)
                ->whereNotIn('batch_items.id', function ($query)  {
                            $query->select('batch_items.id')
                                    ->from('pivot_store_items')
                                    ->join('store_items_list','pivot_store_items.store_item_id','=', 'store_items_list.id')
                                    ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                                    ->where("pivot_store_items.is_recived",1)
                                    ->whereNull("pivot_store_items.deleted_at");
                })
                ->get();
        else:
            $res = \Modules\Master\Entities\PivotStoreItems::select('items.*','batch_items.unique_id','pivot_store_items.id as pivot_id','purchase_entry_batch.amount') 
                ->join('store_items_list','store_items_list.id','=', 'pivot_store_items.store_item_id') 
                ->join('items','items.id','=', 'store_items_list.item_id') 
                ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id') 
                ->join('purchase_entry_batch','purchase_entry_batch.id','=', 'batch_items.batch_id') 
                ->where('store_items_list.store_id',\Auth::guard(master_guard)->user()->store_id)
                ->where("batch_items.unique_id","LIKE","%{$request->term}%")
                ->where('pivot_store_items.is_recived',1)
                ->where('pivot_store_items.is_breakage',0)
                ->where('items.has_unique_id',1)
                ->latest('items.created_at')->get(); 
        endif;
        return response()->json($res);
    }
}
