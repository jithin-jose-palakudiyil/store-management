<?php

namespace Modules\Master\Http\Controllers\Extras;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\MaintenanceType; 
use Modules\Master\Repositories\CommonRepository;
use \View; use Modules\Master\Helpers\Crud;
use Modules\Master\Helpers\ActivityLogHelper;

class MaintenanceTypeController extends Controller
{
    protected $repository;
    public function __construct(MaintenanceType $MaintenanceType)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('extras.maintenance-type');
        $this->createUrl            =   route('extras.maintenance-type.create');  
        $this->createMessage        =   'Maintenance Type is created successfully.';
        $this->createErrorMessage   =   'Maintenance Type is not created successfully.';
        $this->updateMessage        =   'Maintenance Type is updated successfully.';
        $this->updateErrorMessage   =   'Maintenance Type is not updated successfully.';
        $this->deleteMessage        =   'Maintenance Type is deleted successfully.';
        $this->deleteErrorMessage   =   'Maintenance Type is not deleted successfully.'; 
        $this->page_title           =   'Maintenance Type';
        $this->ViewBasePath         =   'master::extras.maintenance_type.';
        $this->repository           =   new CommonRepository($MaintenanceType); 
        View::share('active', 'maintenance_type'); 
        
        $this->middleware('module_permission:maintenance-type-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:maintenance-type-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:maintenance-type-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:maintenance-type-delete', ['only' => ['destroy']]); 
    
    }
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        return \DataTables::of(MaintenanceType::latest())->make(true);   
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    { 
//        ActivityLogHelper::log(
//                \Auth::guard(master_guard)->user()->id,
//                new MaintenanceType(), ['key' => 'index'],
//                'maintenance type', 'You have checked the maintenance type'
//        );
        return view($this->ViewBasePath.'index', [
            'breadcrumb'    =>  [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Maintenance Type',    "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','maintenance-type-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Maintenance Type'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','maintenance-type-edit')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','maintenance-type-delete')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'page_title'    =>  $this->page_title
        ]);
     
       
    }

    /**
     * Show the form for creating a new resource.
     * @param object $Measurement
     * @return Renderable
     */
    public function create(MaintenanceType $MaintenanceType)
    { 
//        ActivityLogHelper::log(
//                \Auth::guard(master_guard)->user()->id,
//                new MaintenanceType(), ['key' => 'create'],
//                'maintenance type', 'You have checked the maintenance type for creating new one !'
//        );
        
        
        return view($this->ViewBasePath.'create', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',            "url" => $this->dashboardUrl ],
                [ "title" => 'Maintenance Type',     "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',               "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'        =>  $this->page_title .' creating',
            'MaintenanceType'   =>$MaintenanceType
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
            'name'=>'required|max:255',
            'days'=>'required|numeric',  
            "status"     =>  "required|numeric",  
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
     * @param object $MaintenanceType
     * @return Renderable
     */
    public function edit(MaintenanceType $MaintenanceType)
    { 
//        ActivityLogHelper::log(
//            \Auth::guard(master_guard)->user()->id,
//            new MaintenanceType(), ['key' => 'edit'],
//            'maintenance type', 'You have checked the edit page of maintenance type for  edit <b>'.$MaintenanceType->name.'</b>',
//            $MaintenanceType
//        );
         
        return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',            "url" => $this->dashboardUrl ],
                [ "title" => 'Maintenance Type',     "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                 "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'        =>  $this->page_title .' editing',
            'MaintenanceType'   =>$MaintenanceType
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param object $MaintenanceType
     * @return Renderable
     */
    public function update(Request $request, MaintenanceType $MaintenanceType)
    {
        $request->validate([
            'name'=>'required|max:255',
            'days'=>'required|numeric',  
            "status"     =>  "required|numeric",  
        ]);
        if(!$request->ajax()):
            Crud::update($this->repository, $MaintenanceType,$request->all(),$this->updateMessage,$this->updateErrorMessage);
            return \Redirect::to($this->defaultUrl );
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif;
    }

    /**
     * Remove the specified resource from storage.
     * @param object $MaintenanceType
     * @return Renderable
     */
    public function destroy(MaintenanceType $MaintenanceType)
    {
        return Crud::destroy(
            $this->repository, $MaintenanceType,$this->deleteMessage,$this->deleteErrorMessage
        );

    }
}
