<?php

namespace Modules\Master\Http\Controllers\Others;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Calibration;
use Modules\Master\Repositories\CommonRepository;
use \View; use \Modules\Master\Helpers\Crud;
use \Exception;
use Modules\Master\Helpers\ActivityLogHelper;

class CalibrationController extends Controller
{
    protected $repository;
    public function __construct(Calibration $Calibration)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('others.calibration');
        $this->createUrl            =   route('others.calibration.create');  
        $this->createMessage        =   'Calibration is created successfully.';
        $this->createErrorMessage   =   'Calibration is not created successfully.';
        $this->updateMessage        =   'Calibration is updated successfully.';
        $this->updateErrorMessage   =   'Calibration is not updated successfully.';
        $this->deleteMessage        =   'Calibration is deleted successfully.';
        $this->deleteErrorMessage   =   'Calibration is not deleted successfully.';  
        $this->repository           =   new CommonRepository($Calibration); 
        $this->page_title           =   'Calibration';
        $this->ViewBasePath         =   'master::others.calibration.';
        View::share('active', 'calibration');
        
        $this->middleware('module_permission:calibration-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:calibration-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:calibration-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:calibration-delete', ['only' => ['destroy']]); 
    
    }
    
      
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
         $query = Calibration::
                with(['hasOneBatchItem' => function ($query) {
                    $query->select('id', 'item_id','unique_id');
                    $query->with('hasOneItem:id,name');
                }]) 
                ->with('hasOneCalibrationType:id,name')
                ->latest();
                
        return \DataTables::of( $query
//                Calibration::with('hasOneItem:id,name')->with('hasOneCalibrationType:id,name')->latest()
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
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Calibration',         "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'page_title'    =>  $this->page_title,
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','calibration-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Calibration'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','calibration-edit')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','calibration-delete')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'updateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','calibration-upload')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
        
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Calibration $Calibration)
    {
       return view($this->ViewBasePath.'create', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
                [ "title" => 'Calibration',      "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',           "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' creating',
            'Calibration'   =>  $Calibration
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
              
            'item_id'=> [ "required","numeric",function ($attribute, $value, $fail)  {
                if($value):
                    $item = \Modules\Master\Entities\Items::where('items.status',1)
                        ->join('batch_items','batch_items.item_id','=', 'items.id')
                        ->where('batch_items.id',$value)->first();
                    if(!$item): $fail('This item not found !.');   endif;
                endif; }
            ], 
            'calibration_type_id'=> [ "required","numeric",function ($attribute, $value, $fail)  {
                if($value):
                    $CalibrationType = \Modules\Master\Entities\CalibrationType::where('status',1)->where('id',$value)->first();
                    if(!$CalibrationType): $fail('This Calibration type not found !.');   endif;
                endif; }
            ],
            'item_name'=>'required|max:255', 'next_date' => 'required', 'calibration_by' => 'required|max:255', 'contact_number' => 'required','contact_email' => 'required|email', "status" =>  "required|numeric",  
        ]);
        $data=[ 'batch_item_id' => $request->item_id, 'calibration_type_id' => $request->calibration_type_id, 'calibration_by' => $request->calibration_by, 'contact_number' => $request->contact_number, 'contact_email' => $request->contact_email, 'status' => $request->status ];
            
        if(!$request->ajax()):
            $error =null; $Crud = Crud::store($this->repository, $data,$this->createMessage,$this->createErrorMessage);
            if($Crud['error']==null && $Crud['response']):
                try {  \Modules\Master\Entities\PivotCalibration::create( ['calibration_id'=>$Crud['response']->id, 'date'=>$request->next_date ]); } catch (Exception $ex) { $error = $ex->getMessage(); }
            endif;
            if($error!=null):session()->flash('flash-error-message',$this->createErrorMessage.'<br/> '.$error); endif;
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
    public function edit(Calibration $Calibration)
    {
        //hasManyCalibrationDates
        //Maintenance
//        $Calibration = $Calibration->with('hasOneItem:id,name')->where('id',$Calibration->id)->first();
         $Calibration = $Calibration->with(['hasOneBatchItem' => function ($query) {
                    $query->select('id', 'item_id','unique_id');
                    $query->with('hasOneItem:id,name');
                }]) 
                ->with('hasOneCalibrationType:id,name')
                        ->where('id',$Calibration->id)->first();
         return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Calibration',         "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'Calibration'  =>$Calibration,
            'edit'=>true
                 
        ]); 
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Calibration $Calibration)
    {
        $request->validate([  
            'calibration_by' => 'required|max:255',
            'contact_number' => 'required',
            'contact_email' => 'required|email',
            "status" =>  "required|numeric",  
        ]);
        $data=[  'calibration_by' => $request->calibration_by, 'contact_number' => $request->contact_number, 'contact_email' => $request->contact_email, 'status' => $request->status ];
         
        if(!$request->ajax()):
            $Crud =  Crud::update($this->repository, $Calibration,$data,$this->updateMessage,$this->updateErrorMessage);
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
    public function destroy(Calibration $Calibration)
    {
        return Crud::destroy(
            $this->repository, $Calibration,$this->deleteMessage,$this->deleteErrorMessage
        );
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ItemAutocomplete(Request $request)
    {
        $res = \Modules\Master\Entities\Items::select("items.name","batch_items.id","batch_items.unique_id",
                "batch_items.unique_id AS unique_ids",
                    \DB::raw(
                                "IF(
                                    (
                                        select count(*) as cnt from calibration
                                        INNER JOIN batch_items on batch_items.id = calibration.batch_item_id
                                        WHERE batch_items.unique_id =unique_ids
                                    ) >= 1,true,false 
                                ) as has_calibration"
                            )
                
                
                )
                ->join('batch_items','batch_items.item_id','=', 'items.id')     
                ->where("items.name","LIKE","%{$request->term}%")
                ->where("items.status",1)
                ->get();
                
//        $res = \Modules\Master\Entities\Items::select("name","id")
//                ->where("name","LIKE","%{$request->term}%")
//                ->where("status",1)
//                ->get();
    
        return response()->json($res);
    }
    
    /**
     * Display a Calibration of the Item Category.
     * @return Response
     */
    public function getCalibrationDays(Request $request, $id)
    {
        $day=null;
        if( $request->ajax()):
             
//            if($request->exists('maintenance_id')&& $request->maintenance_id!=null && is_numeric($request->maintenance_id)):
//                $Maintenance = Maintenance::with('hasManyMaintenanceDates')->where('id',$request->maintenance_id)->first();
//                if(isset($Maintenance->hasManyMaintenanceDates)):
//                    $MaintenanceDates= $Maintenance->hasManyMaintenanceDates->pluck('date')->toArray();
//                endif;
//            endif;
//       
            $CalibrationType = \Modules\Master\Entities\CalibrationType::find($id);
            if($CalibrationType):
                $day = $CalibrationType->days;
            endif;
            return response()->json(['day' => $day]); 
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
        $calibration = Calibration::with('hasOneItem:id,name')->with(['hasManyCalibrationDates' => function ($query) { $query->where('status', '0');$query->orWhere('status', '2');}])->where('id',$id)->first(); 
        if($calibration):
            $ListModel = $this->ListModel($calibration);
        endif;
        $html =  \View::make($this->ViewBasePath.'partials.master', compact('ListModel','calibration'))->render();
        return $html;
    }
    
    /**
     * Display a List of the Item usage.
     * @return Response
     */
    
    public function ListModel($calibration){
        $ListModel = \Modules\Master\Entities\PivotCalibration::where('calibration_id',$calibration->id)
                ->orderBy('id', 'DESC')
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
                'calibration_id'=> ["required","numeric",function ($attribute, $value, $fail){
                    if($value):
                        $item = Calibration::where('status',1)->where('id',$value)->first();
                        if(!$item): $fail('The item has not found for maintenance');   endif;
                    endif; 
                }], 
                'date'=> ["required","numeric",function ($attribute, $value, $fail){
                    $calibration_id =\Request::get('calibration_id');
                    $LPivotCalibration = \Modules\Master\Entities\PivotCalibration::where('calibration_id',$calibration_id)->where('id', $value)->first();; 
                    if(!$LPivotCalibration): $fail('This date has not found for calibration');   endif;
                    
                }],
                "status"     =>  "required|numeric","completion_date"     =>  "required",   
        ]);
                
            try { 
                $LPivotCalibration = \Modules\Master\Entities\PivotCalibration::where('calibration_id',$request->calibration_id)->where('id', $request->date)->first();; 
                if($LPivotCalibration):
                    $data = ['comments'=>$request->comments,'status'=>$request->status,'completion_date'=>$request->completion_date];
                    $LPivotCalibration->update($data); 
                    $calibration = Calibration::with('hasOneItem:id,name')->with(['hasManyCalibrationDates' => function ($query) { $query->where('status', 0);$query->orWhere('status', 2);}])->where('id',$request->calibration_id)->first(); 
                    ActivityLogHelper::log(
                        \Auth::guard(master_guard)->user()->id,
                        new Calibration(), ['key' => 'update'],
                        'maintenance', 'You have updated the calibration status',$calibration
                    );$html =  \View::make($this->ViewBasePath.'partials.statusUpdateForm', compact('calibration'))->render();
                    $ListModel = $this->ListModel($calibration);
                    $list =  \View::make($this->ViewBasePath.'partials.DateList', compact('ListModel'))->render();
                endif;   
            } catch (Exception $ex) { $error = $ex->getMessage(); }
            
            if($error == null): 
                return response()->json(['sucess' => true,'html' => $html,'list'=>$list,'message'=>'<div class="alert alert-success no-border">
                                                                             <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                                                             <span class="text-semibold">Well done!</span> You successfully updated the calibration.
                                                                 </div>']); 
            else: 
                return response()->json(['sucess' => false,'html' => $html,'list'=>$list,'message'=>'<div class="alert alert-danger alert-bordered">
                                                                             <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                                                             <span class="text-semibold">Oh snap!</span> Change a few things up and try submitting again.
                                                                 </div>']);  
            endif;
         
    }
    
    
}
