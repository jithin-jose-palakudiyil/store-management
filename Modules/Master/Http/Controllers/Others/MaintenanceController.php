<?php

namespace Modules\Master\Http\Controllers\Others;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Maintenance;
use Modules\Master\Repositories\CommonRepository;
use \View; use \Modules\Master\Helpers\Crud;
use \Exception;
use Modules\Master\Helpers\ActivityLogHelper;

class MaintenanceController extends Controller
{
    protected $repository;
    public function __construct(Maintenance $Maintenance)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('others.maintenance');
        $this->createUrl            =   route('others.maintenance.create');  
        $this->createMessage        =   'Maintenance is created successfully.';
        $this->createErrorMessage   =   'Maintenance is not created successfully.';
        $this->updateMessage        =   'Maintenance is updated successfully.';
        $this->updateErrorMessage   =   'Maintenance is not updated successfully.';
        $this->deleteMessage        =   'Maintenance is deleted successfully.';
        $this->deleteErrorMessage   =   'Maintenance is not deleted successfully.';  
        $this->repository           =   new CommonRepository($Maintenance); 
        $this->page_title           =   'Maintenance';
        $this->ViewBasePath         =   'master::others.maintenance.';
        View::share('active', 'maintenance');
        
        $this->middleware('module_permission:maintenance-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:maintenance-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:maintenance-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:maintenance-delete', ['only' => ['destroy']]); 
    
    }
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        $query = Maintenance::
                with(['hasOneBatchItem' => function ($query) {
                    $query->select('id', 'item_id','unique_id');
                    $query->with('hasOneItem:id,name');
                }]) 
                ->with('hasOneMaintenanceType:id,name')
                ->latest();
        return \DataTables::of(
                $query
                )->make(true);   
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view($this->ViewBasePath.'index', [
            'breadcrumb'    =>  [ 
                [ "title" => 'Dashboard',       "url" => $this->dashboardUrl ],
                [ "title" => 'Maintenance',     "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'page_title'    =>  $this->page_title,
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','maintenance-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Maintenance'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','maintenance-edit')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','maintenance-delete')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'updateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','maintenance-upload')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Maintenance $Maintenance)
    {
         return view($this->ViewBasePath.'create', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
                [ "title" => 'Maintenance',      "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',           "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' creating',
            'Maintenance'   =>  $Maintenance
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ItemAutocomplete(Request $request)
    {
//        dd($request->all());
        $res = [];
        if(\Auth::guard(master_guard)->user()->role=='master'):
            $res = \Modules\Master\Entities\Items::select(
                    "items.name","batch_items.id","batch_items.unique_id","batch_items.unique_id AS unique_ids",
                    \DB::raw(
                                "IF(
                                    (
                                        select count(*) as cnt from maintenance
                                        INNER JOIN batch_items on batch_items.id = maintenance.batch_item_id
                                        WHERE batch_items.unique_id =unique_ids
                                    ) >= 1,true,false 
                                ) as has_maintance"
                            )
                    )
                ->join('batch_items','batch_items.item_id','=', 'items.id')     
                ->where("items.name","LIKE","%{$request->term}%")
                ->where("items.status",1)
                ->get();    
        else:
            $res = \Modules\Master\Entities\PivotStoreItems::
                select("items.name","batch_items.id","batch_items.unique_id")
//                select('items.*','batch_items.unique_id','pivot_store_items.id as pivot_id','purchase_entry_batch.amount') 
            ->join('store_items_list','store_items_list.id','=', 'pivot_store_items.store_item_id') 
            ->join('items','items.id','=', 'store_items_list.item_id') 
            ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id') 
            ->join('purchase_entry_batch','purchase_entry_batch.id','=', 'batch_items.batch_id') 
            ->where('store_items_list.store_id',\Auth::guard(master_guard)->user()->store_id)
            ->where("items.name","LIKE","%{$request->term}%")
            ->where('pivot_store_items.is_recived',1)
//            ->where('pivot_store_items.is_breakage',0)
            ->where('items.has_unique_id',1)
            ->latest('items.created_at')->get(); 
        endif;
        
    
        return response()->json($res);
    }
    
    
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ItemSuppliersAutocomplete(Request $request)
    {
        $res = \Modules\Master\Entities\Suppliers::select("name","email",'phone','id') 
                ->where("name","LIKE","%{$request->term}%")
                ->where("status",1)
                ->get();
    
        return response()->json($res);
    }
    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
       
         $request->validate([
            'item_name'=>'required|max:255',  
            'item_id'=> [ "required","numeric",function ($attribute, $value, $fail)  {
                if($value):
                    $item = \Modules\Master\Entities\Items::where('items.status',1)
                        ->join('batch_items','batch_items.item_id','=', 'items.id') ->where('batch_items.id',$value)->first();
                    if(!$item): $fail('This item not found !.');   endif;
                endif; }
            ], 
            'maintenance_type_id'=> [ "required","numeric",function ($attribute, $value, $fail)  {
                if($value):
                    $MaintenanceType = \Modules\Master\Entities\MaintenanceType::where('status',1)->where('id',$value)->first();
                    if(!$MaintenanceType): $fail('This maintenance type not found !.');   endif;
                endif; }
            ],
            'company_name'              => 'required|max:255', 
            'contact_number'            => 'required',
            'contact_email'             => 'required|email',
            "status" => "required|numeric", "date" => "required|array|min:1",
            'date'=> ["required","array","min:1",function ($attribute, $value, $fail) 
                { 
                    $trimmedArray = array_filter( $value);
                    if(!empty($trimmedArray)):
                        if(count($trimmedArray) !== count(array_unique($trimmedArray))):
                            $fail('The :attribute has unique values.'); 
                        endif;
                    endif; 
                }
            ],
            
        ]);
         
        $data =[
            'batch_item_id'=>$request->item_id, 'maintenance_type_id'=>$request->maintenance_type_id,
            'company_name'=>$request->company_name, 'contact_number'=>$request->contact_number,
            'contact_email'=>$request->contact_email, 'status'=>$request->status,
         ];
        
        if(!$request->ajax()):
            $error =null;$Crud = Crud::store($this->repository, $data,$this->createMessage,$this->createErrorMessage);
            if($Crud['error']==null && $Crud['response']):
                try
                { 
                    $dates=[];
                    foreach ($request->date as $key => $values): 
                        if($values): 
                            $dates[$key]['maintenance_id']=$Crud['response']->id; $dates[$key]['date']=$values; $dates[$key]['status']=0;
                         endif;
                    endforeach;
                    if(!empty($dates)): \Modules\Master\Entities\PivotMaintenance::insert($dates); endif;
                   
                } catch (Exception $ex) { $error = $ex->getMessage(); }
               if($error!=null):
                    session()->flash('flash-error-message',$this->createErrorMessage.'<br/> '.$error);
               endif;
            endif; 
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
    public function edit(Maintenance $Maintenance)
    {
        //Maintenance
//        $Maintenance = $Maintenance->with('hasOneItem:id,name')->where('id',$Maintenance->id)->first();
        $Maintenance = $Maintenance->with(['hasOneBatchItem' => function ($query) {
                    $query->select('id', 'item_id','unique_id');
                    $query->with('hasOneItem:id,name');
                }]) 
                ->with('hasOneMaintenanceType:id,name')
                        ->where('id',$Maintenance->id)->first();
        return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Maintenance',         "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'Maintenance'   =>  $Maintenance,
            'edit'          =>  true
        ]); 
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Maintenance $Maintenance)
    {
        
        $request->validate([
            'company_name'              => 'required|max:255', 
            'contact_number'            => 'required',
            'contact_email'             => 'required|email',
            "status" => "required|numeric", 
            'date'=> ["required","array","min:1",function ($attribute, $value, $fail) 
                {
            
                    $trimmedArray = array_filter( $value);
                    if(!empty($trimmedArray)):
                        if(count($trimmedArray) !== count(array_unique($trimmedArray))):
                            $fail('The :attribute has unique values.'); 
                        endif;
                    endif;

                }
            ],  
        ]);
        $data =[
            'company_name'=>$request->company_name, 'contact_number'=>$request->contact_number,
            'contact_email'=>$request->contact_email, 'status'=>$request->status,
        ];
        
        if(!$request->ajax()):
            $dates=[]; $error = null; $Crud = Crud::update($this->repository, $Maintenance,$data,$this->updateMessage,$this->updateErrorMessage);
        
            if($Crud['error']==null && $Crud['response']): 
                try{

                    \Modules\Master\Entities\PivotMaintenance::where('maintenance_id',$Maintenance->id)->where('status',0)->delete(); 
                    foreach ($request->date as $key => $values): 
                        if($values):  
                            $dates[$key]['maintenance_id']=$Maintenance->id; $dates[$key]['date']=$values; $dates[$key]['status']=0;
                         endif;
                    endforeach;
                    if(!empty($dates)): \Modules\Master\Entities\PivotMaintenance::insert($dates); endif;
                   
                    
                } catch (Exception $ex) { $error = $ex->getMessage(); }
               if($error!=null): session()->flash('flash-error-message',$this->updateErrorMessage.'<br/> '.$error); endif;
            endif;
            
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
    public function destroy(Maintenance $Maintenance)
    {
         return Crud::destroy(
            $this->repository, $Maintenance,$this->deleteMessage,$this->deleteErrorMessage
        );
    }
    
    /**
     * Display a Measurements of the Item Category.
     * @return Response
     */
    public function getMaintenanceDays(Request $request, $id)
    {
        $html=null; $Maintenance =null; $MaintenanceDates =[];
        if( $request->ajax()):
             
            if($request->exists('maintenance_id')&& $request->maintenance_id!=null && is_numeric($request->maintenance_id)):
                $Maintenance = Maintenance::with('hasManyMaintenanceDates')->where('id',$request->maintenance_id)->first();
                if(isset($Maintenance->hasManyMaintenanceDates)):
                    $MaintenanceDates= $Maintenance->hasManyMaintenanceDates->pluck('date')->toArray();
                endif;
            endif;
       
            $MaintenanceType = \Modules\Master\Entities\MaintenanceType::find($id);
            if($MaintenanceType):
                $year =\Carbon\Carbon::parse(date("Y").'-01')->daysInYear;
                $days =$year/$MaintenanceType->days; 
                if (str_contains($days, '.') == false) {
                    $input = $days;
                }else{
                    $strrpos = strrpos( $days, '.');
                    $input = substr($days, 0, $strrpos);
                }
                 
                if($days >=0):  
                    $i=1;$j=1;
                    for ($x = 1; $x <= $input; $x++) :
                        $danger =null; $disabled =''; 
                        if(isset($MaintenanceDates[$x-1])):
                            $date = $MaintenanceDates[$x-1];
                            $where = $Maintenance->hasManyMaintenanceDates->where('date',$date)->first();
                            if($where && $where->status!=0): $disabled='disabled=""'; endif;
                        endif;
                       
                        if($x==1): $danger = '<span class="text-danger">*</span>';  endif;
                        $html.='<div class="col-md-3">
                            <div class="form-group ">
                                <label for="contact_email">Date '.$i.''.$danger.'</label>
                                <input '.$disabled.' readonly="" type="text" class="form-control datepicker-menus" id="date_'.$i.'" name="date[]"  placeholder="Date '.$i.'" value="'.(isset($MaintenanceDates[$x-1]) ? $MaintenanceDates[$x-1] : '').'" > 
                            </div> 
                        </div>'; 
                        if($j==4): $html.= '<div class="row"></div>'; $j=0; endif; 
                       $i++; $j++;
                    endfor;
                endif; 
            endif;
            return response()->json(['html' => $html]); 
        else:  abort(404); endif;
    }
    
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getUpdateModel(Request $request, $id)
    {   
        if( $request->ajax()):
            $html =  $this->getForm( $id);
            return response()->json(['html' => $html]);
        else:  abort(404); endif;
         
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getForm($id)
    {
        $ListModel=null;  
        $maintenance = Maintenance::with('hasOneItem:id,name')->with(['hasManyMaintenanceDates' => function ($query) { $query->where('status', '0');$query->orWhere('status', '2');}])->where('id',$id)->first(); 
        if($maintenance):
            $ListModel = $this->ListModel($maintenance);
        endif;
        $html =  \View::make($this->ViewBasePath.'partials.master', compact('ListModel','maintenance'))->render();
        return $html;
    }
     /**
     * Display a List of the Item usage.
     * @return Response
     */
    
    public function ListModel($maintenance){
        $ListModel = \Modules\Master\Entities\PivotMaintenance::where('maintenance_id',$maintenance->id)
//                ->orderBy('id', 'DESC')
                ->get();; 
        $html=null;
        if($ListModel->isNotEmpty()):
            foreach ($ListModel as $key => $value) :
                if($value->status==0):
                    $status ='<span class="label label-primary">Initialized</span>';
                elseif($value->status==1):
                    $status ='<span class="label label-success">Completed</span>';
                elseif($value->status==2):
                    $status ='<span class="label label-default">Hold</span>';
                elseif($value->status==3):
                    $status ='<span class="label label-danger">Rejected</span>';
                endif;
            $html.='<tr>
                        <td>'.$value->date.'</td>
                        <td>'.$value->comments.'</td>
                        <td>'.$status.'</td>
                        <td>'.$value->completion_date.'</td>
                    </tr>';
            endforeach;
        endif;
        return $html;
    }       
    
    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function statusUpdate(Request $request){
       $error = null;
        $request->validate
           ([ 
                'maintenance_id'=> ["required","numeric",function ($attribute, $value, $fail){
                    if($value):
                        $item = Maintenance::where('status',1)->where('id',$value)->first();
                        if(!$item): $fail('The item has not found for maintenance');   endif;
                    endif; 
                }], 
                'date'=> ["required","numeric",function ($attribute, $value, $fail){
                    $maintenance_id =\Request::get('maintenance_id');
                    $LPivotMaintenance = \Modules\Master\Entities\PivotMaintenance::where('maintenance_id',$maintenance_id)->where('id', $value)->first();; 
                    if(!$LPivotMaintenance): $fail('This date has not found for maintenance');   endif;
                    
                }],
                "status"     =>  "required|numeric",   "completion_date"     =>  "required",  
        ]);
                
            try { 
                $LPivotMaintenance = \Modules\Master\Entities\PivotMaintenance::where('maintenance_id',$request->maintenance_id)->where('id', $request->date)->first();; 
                if($LPivotMaintenance):
                    $data = ['comments'=>$request->comments,'status'=>$request->status,'completion_date'=>$request->completion_date];
                    $LPivotMaintenance->update($data); 
                    $maintenance = Maintenance::with('hasOneItem:id,name')->with(['hasManyMaintenanceDates' => function ($query) { $query->where('status', 0);$query->orWhere('status', 2);}])->where('id',$request->maintenance_id)->first(); 
                    ActivityLogHelper::log(
                        \Auth::guard(master_guard)->user()->id,
                        new Maintenance(), ['key' => 'update'],
                        'maintenance', 'You have updated the maintenance status',$maintenance
                    );$html =  \View::make($this->ViewBasePath.'partials.statusUpdateForm', compact('maintenance'))->render();
                    $ListModel = $this->ListModel($maintenance);
                    $list =  \View::make($this->ViewBasePath.'partials.maintenanceDateList', compact('ListModel'))->render();
                endif;   
            } catch (Exception $ex) { $error = $ex->getMessage(); }
            
            if($error == null): 
                return response()->json(['sucess' => true,'html' => $html,'list'=>$list,'message'=>'<div class="alert alert-success no-border">
                                                                             <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                                                             <span class="text-semibold">Well done!</span> You successfully updated the maintenance.
                                                                 </div>']); 
            else: 
                return response()->json(['sucess' => false,'html' => $html,'list'=>$list,'message'=>'<div class="alert alert-danger alert-bordered">
                                                                             <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                                                             <span class="text-semibold">Oh snap!</span> Change a few things up and try submitting again.
                                                                 </div>']);  
            endif;
         
    }
}
