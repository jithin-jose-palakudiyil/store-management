<?php

namespace Modules\Master\Http\Controllers\Stock;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\PurchaseEntry;
use Modules\Master\Repositories\CommonRepository;
use \View; use Modules\Master\Helpers\Crud;
use \Exception; use Illuminate\Validation\ValidationException;

class PurchaseEntryController extends Controller
{
         public function __construct(PurchaseEntry $PurchaseEntry)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('stock.purchase-entry');
        $this->createUrl            =   route('stock.purchase-entry.create');  
        $this->createMessage        =   'Purchase Entry is created successfully.';
        $this->createErrorMessage   =   'Purchase Entry  is not created successfully.';
        $this->updateMessage        =   'Purchase Entry  is updated successfully.';
        $this->updateErrorMessage   =   'Purchase Entry  is not updated successfully.';
        $this->deleteMessage        =   'Purchase Entry  is deleted successfully.';
        $this->deleteErrorMessage   =   'Purchase Entry  is not deleted successfully.'; 
        $this->page_title           =   'Purchase Entry';
        $this->ViewBasePath         =   'master::stock.purchase-entry.';
        $this->repository           =   new CommonRepository($PurchaseEntry); 
        View::share('active', 'purchase-entry'); 
        
        $this->middleware('module_permission:purchase-entry-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:purchase-entry-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:purchase-entry-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:purchase-entry-delete', ['only' => ['destroy']]); 
    
    }
    
      /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        return \DataTables::of(PurchaseEntry::latest())->make(true);   
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view($this->ViewBasePath.'index', [
            'breadcrumb'    =>  [ 
                [ "title" => 'Dashboard',           "url" =>  $this->dashboardUrl ],
                [ "title" => 'Purchase Entry',      "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','purchase-entry-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Purchase Entry'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','purchase-entry-edit')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','purchase-entry-delete')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'viewBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','purchase-entry-view')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'page_title'    =>  $this->page_title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(PurchaseEntry $PurchaseEntry)
    {
       return view($this->ViewBasePath.'create', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',            "url" =>   $this->dashboardUrl ],
                [ "title" => 'Purchase Entry',       "url" =>   $this->defaultUrl, ],
                [ "title" => 'Create',               "url" =>   'javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'      =>  $this->page_title .' creating',
            'PurchaseEntry'   =>  $PurchaseEntry
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
            'invoice_id'=>'required',
            'invoice_date'=>'required',
            'total_amount'=>'required|numeric', 
//            'entry_date' =>'required',
            "invoice_file"     => 'mimes:jpg,png,jpeg,gif,pdf,JPG,PNG,JPEG,GIF,PDF|max:3000', // max 3mb, 
            "purchase_entry_file"     => 'mimes:jpg,png,jpeg,gif,pdf,JPG,PNG,JPEG,GIF,PDF|max:3000', // max 3mb, 
            'supplier_id'=>'required', 
           
        ]);
//        dd($request->all());
        $item_id = $request->item_id; $hdn_item_id = $request->hdn_item_id; $expiry_date = $request->expiry_date;
        $quantity = $request->quantity; $generate_id = $request->generate_id; $amount = $request->amount;
        if   (
                (  count($item_id) ==  count($hdn_item_id)  )  &&
                (  count($hdn_item_id) ==  count($quantity)  ) && 
                (  count($quantity) ==  count($amount)  ) && 
                (  count($amount) ==  count($item_id)  )
            ):
                $error =null;
                if(!$request->ajax()):
                try
                { 
                    $databaseName = \Config::get('database.connections');
                    $purchase_entry = $this->purchase_entry($request->all()); 
                    if(isset($purchase_entry['response']) && $purchase_entry['response']): 
                        foreach ($hdn_item_id as $key => $value):
                            $_item_id = trim(substr($item_id[$key], strrpos($item_id[$key], '-') + 1));
                            $item = \Modules\Master\Entities\Items::find($value);
                            if($item && ($_item_id == $item->id)):
//                                dd($item);
                                //genarate batch
                                $batchArray=[
                                    'purchase_entry_id' => $purchase_entry['response']->id, 
                                    'item_id' => $item->id,
                                    'generate_id' => ($item->has_unique_id==1) ? 1 : 2,
                                    'expiry_date' => (isset($request['expiry_date'][$key])) ? $request->expiry_date[$key] : null,
                                    'quantity' =>$request->quantity[$key],
                                    'amount' =>$request->amount[$key],
                                    'make_model' =>(isset($request['make_model'][$key])) ? $request->make_model[$key] : null,
                                ]; 
                                $batch = \Modules\Master\Entities\PurchaseEntryBatch::create($batchArray);
                                if($batch):
                                    if($item->has_unique_id==1):
                                        //get last insert id;
                                        $y=0;
                                        for ($x = 1; $x <= $quantity[$key]; $x++) :
                                            $AUTO_INCREMENT= null; 
                                            $table = \DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$databaseName['mysql']['database']."' AND TABLE_NAME = 'batch_items'");
                                            if (!empty($table)) :
                                                $AUTO_INCREMENT = $table[0]->AUTO_INCREMENT;
                                            endif;
                                            if($AUTO_INCREMENT):
                                                $BatchItems =['unique_id'=>'UID'.$AUTO_INCREMENT,'batch_id'=>$batch->id,'item_id'=>$item->id,'expiry_date'=>$request->expiry_date[$key]];
                                                \Modules\Master\Entities\BatchItems::create($BatchItems);
                                                $y++;
                                            endif; 
                                        endfor;
                                        if($y==$quantity[$key]):
                                            $quantityItem=$item->quantity+$request->quantity[$key];
                                            $item->update(['quantity'=>$quantityItem]);
                                        endif;
                                        
                                    else:
                                        
                                        //has_unique_id
                                        if($item->has_unique_id != 1): 
                                            $quantityItem=$item->quantity+$request->quantity[$key];
                                            $item->update(['quantity'=>$quantityItem]);
                                        else: 
                                            $purchase_entry['response']->forceDelete();
                                            $batch->forceDelete();
                                            return \Redirect::back()->withErrors(['items' => 'Item'.$item->name.' has issue!, must select Generate Id YES and try submitting again.']);    
                                            die();
                                        endif;
                                    endif; 
                                endif;
                            endif;
                        endforeach;
                    endif;
                } catch (Exception $ex) { $error=$ex->getMessage(); } 
                if($error==null && isset($purchase_entry['response']->id)): 
                    session()->flash('flash-success-message',$this->createMessage);
                else:
                    session()->flash('flash-error-message',$this->createErrorMessage.'<br/> '.$error);
                endif;
                return \Redirect::to($this->defaultUrl ); 
            else:
                return response()->json(['message' => 'Page not found!'], 404);
            endif;
        else: throw ValidationException::withMessages(['items' => 'Items has issue!, Change a few things up and try submitting again.']); endif;
       
       
       
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function purchase_entry ($request)
    {
        $data =[ 'invoice_id'=>$request['invoice_id'], 'invoice_date'=>$request['invoice_date'],'total_amount'=>$request['total_amount'],'entry_date'=>date("d-m-Y") ]; 
        if(isset($request['invoice_file']) && $request['invoice_file']):
            $allowedfileExtension = ['jpg','png','jpeg','gif','pdf','JPG','PNG','JPEG','GIF','PDF'];
            $path = public_path().'/uploads/purchase_entry/invoice';
            $thumb_icon = \App\Helpers\FileHelper::upload($request['invoice_file'], $path, $allowedfileExtension); 
            $data['invoice_file'] = $thumb_icon['file_name'];
        endif;
        if(isset($request['purchase_entry_file']) && $request['purchase_entry_file']):
            $allowedfileExtension = ['jpg','png','jpeg','gif','pdf','JPG','PNG','JPEG','GIF','PDF'];
            $path = public_path().'/uploads/purchase_entry/purchase';
            $thumb_icon = \App\Helpers\FileHelper::upload($request['purchase_entry_file'], $path, $allowedfileExtension); 
            $data['purchase_entry_file'] = $thumb_icon['file_name'];
        endif;
        $supplier_id = $request['supplier_id'];
        if($request['supplier_id'] =='other'): 
            $_supplier = $this->AddSupplier($request); 
            if($_supplier): $supplier_id = $_supplier; endif;
        endif;
        $data['supplier_id'] = $supplier_id;
        return Crud::store($this->repository, $data,$this->createMessage,$this->createErrorMessage,false);          
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function AddSupplier($request)
    {   
        $supplier_id = null;
        $supplier = [   'name' => $request['supplier_name'],
                        'email' => $request['supplier_email'],
                        'phone'=> $request['supplier_phone'],
                        'address'=> $request['supplier_address'],
        ];
        $_supplier= \Modules\Master\Entities\Suppliers::create($supplier);
        if($_supplier): $supplier_id = $_supplier->id; endif;
        return $supplier_id;
    }

    /**
     * show the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request, PurchaseEntry $PurchaseEntry)
    {
        $PurchaseEntry =   $PurchaseEntry
                 ->with(['hasManyBatch' => function ($query) {
                    $query->with('hasManyBatchItems');
                    $query->with(['hasOneItem' => function ($querys) {
                        $querys->with('hasOneMeasurements') ;
                    }]);
                    
                }])
                ->where('id',$PurchaseEntry->id)->first();
               
        return view($this->ViewBasePath.'show', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',            "url" =>   $this->dashboardUrl ],
                [ "title" => 'Purchase Entry',       "url" =>   $this->defaultUrl, ],
                [ "title" => 'Show',               "url" =>   'javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'      =>  $this->page_title .' view',
            'PurchaseEntry'   =>  $PurchaseEntry
        ]); 
          
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ItemAutocomplete(Request $request)
    {
        $res = \Modules\Master\Entities\Items::with('hasOneMeasurements:id,short_code')
//                ->select('id','name')
                ->where("name","LIKE","%{$request->term}%")
                ->where("status",1)
                ->get();
    
        return response()->json($res);
    }
    
      /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ItemModel(Request $request)
    {
      
        if( $request->ajax()):
            $html =  \View::make($this->ViewBasePath.'partials.model', compact('request'))->render();
            return response()->json(['html' => $html]);
        else:  abort(404); endif;
        
    }
    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function StoreItem(Request $request)
    {
        
        $request->validate([
            'name'=>'required|max:255',
            'category_id'=>'required|numeric',
            'measurement_id'=>'required|numeric',
            'has_unique_id'=>'required|numeric',
        ]);
        if($request->ajax()):
            $error = $create = null;
            try{
                $create = \Modules\Master\Entities\Items::create($request->all());
                if($create):
                    $create =  \Modules\Master\Entities\Items::with('hasOneMeasurements:id,short_code')->where('id',$create->id)->first();
                endif; 
            } catch (Exception $ex) { $error = $ex->getMessage();} 
            return response()->json(['error' => $error,'create'=> $create], 200);
        else:
            return response()->json(['error' => '   <div class="alert alert-danger alert-bordered">
                                                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                                        <span class="text-semibold">Oh snap!</span> Change a few things up and try submitting again.
                                                    </div>'], 200);
        endif;
        
    }

    
}
