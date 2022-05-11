<?php

namespace Modules\Master\Http\Controllers\Extras;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\CalibrationType;
use Modules\Master\Repositories\CommonRepository;
use \View; use Modules\Master\Helpers\Crud;

class CalibrationTypeController extends Controller
{
     protected $repository;
    public function __construct(CalibrationType $CalibrationType)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('extras.calibration-type');
        $this->createUrl            =   route('extras.calibration-type.create');  
        $this->createMessage        =   'Calibration Type is created successfully.';
        $this->createErrorMessage   =   'Calibration Type is not created successfully.';
        $this->updateMessage        =   'Calibration Type is updated successfully.';
        $this->updateErrorMessage   =   'Calibration Type is not updated successfully.';
        $this->deleteMessage        =   'Calibration Type is deleted successfully.';
        $this->deleteErrorMessage   =   'Calibration Type is not deleted successfully.'; 
        $this->page_title           =   'Calibration Type';
        $this->ViewBasePath         =   'master::extras.calibration_type.';
        $this->repository           =   new CommonRepository($CalibrationType); 
        View::share('active', 'calibration_type'); 
        
        $this->middleware('module_permission:calibration-type-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:calibration-type-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:calibration-type-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:calibration-type-delete', ['only' => ['destroy']]); 
    
    }
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        return \DataTables::of(CalibrationType::latest())->make(true);   
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
                [ "title" => 'Calibration Type',    "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','calibration-type-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Calibration Type'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','calibration-type-edit')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','calibration-type-delete')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'page_title'    =>  $this->page_title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(CalibrationType $CalibrationType)
    {
        return view($this->ViewBasePath.'create', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',            "url" => $this->dashboardUrl ],
                [ "title" => 'Calibration Type',     "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',               "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'        =>  $this->page_title .' creating',
            'CalibrationType'   =>  $CalibrationType
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
            'name'      =>  'required|max:255',
            'days'      =>  'required|numeric',  
            "status"    =>  "required|numeric",  
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
    public function edit(CalibrationType $CalibrationType)
    {
         return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',            "url" => $this->dashboardUrl ],
                [ "title" => 'Calibration Type',     "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                 "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'        =>  $this->page_title .' editing',
            'CalibrationType'   =>  $CalibrationType
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, CalibrationType $CalibrationType)
    {
       $request->validate([
            'name'      =>  'required|max:255',
            'days'      =>  'required|numeric',  
            "status"    =>  "required|numeric",  
        ]);
        if(!$request->ajax()):
            Crud::update($this->repository, $CalibrationType,$request->all(),$this->updateMessage,$this->updateErrorMessage);
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
    public function destroy(CalibrationType $CalibrationType)
    {
        return Crud::destroy(
            $this->repository, $CalibrationType,$this->deleteMessage,$this->deleteErrorMessage
        );
    }
}
