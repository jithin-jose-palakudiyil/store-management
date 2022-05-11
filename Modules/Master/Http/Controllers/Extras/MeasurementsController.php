<?php

namespace Modules\Master\Http\Controllers\Extras;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Measurements;
use Modules\Master\Repositories\CommonRepository;
use \View; use \Modules\Master\Helpers\Crud;
 use Modules\Master\Helpers\ActivityLogHelper;
 
class MeasurementsController extends Controller
{
    protected $repository;
    public function __construct(Measurements $Measurement)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('extras.measurements');
        $this->createUrl            =   route('extras.measurements.create');  
        $this->createMessage        =   'Measurement is created successfully.';
        $this->createErrorMessage   =   'Measurement is not created successfully.';
        $this->updateMessage        =   'Measurement is updated successfully.';
        $this->updateErrorMessage   =   'Measurement is not updated successfully.';
        $this->deleteMessage        =   'Measurement is deleted successfully.';
        $this->deleteErrorMessage   =   'Measurement is not deleted successfully.';  
        $this->repository           =   new CommonRepository($Measurement); 
        $this->page_title           =   'Measurements';
        $this->ViewBasePath         =   'master::extras.measurements.';
        View::share('active', 'measurements');
        
        $this->middleware('module_permission:measurement-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:measurement-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:measurement-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:measurement-delete', ['only' => ['destroy']]); 
    
    }
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        return \DataTables::of(Measurements::latest())->make(true);   
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
                [ "title" => 'Measurements',    "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'page_title'    =>  $this->page_title,
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','measurement-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Measurement'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','measurement-edit')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','measurement-delete')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
        ]);     
    }

    /**
     * Show the form for creating a new resource.
     * @param object $Measurement
     * @return Renderable
     */
    public function create(Measurements $Measurement)
    {
         return view($this->ViewBasePath.'create', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
                [ "title" => 'Measurements',     "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',           "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' creating',
            'Measurement'   =>  $Measurement
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
            'short_code'=> ["required","max:255",function ($attribute, $value, $fail) 
                {
                    if($value):
                        $short_code = Measurements::withTrashed()->where('short_code',$value)->first();
                        if($short_code):
                            $fail('The :attribute has already been taken.');  
                        endif;
                    endif;

                }
            ],  
            "status"     =>  "required|numeric",  
        ],['short_code.required'=>'The short code field is required.','short_code.max'=>'The short code must not be greater than 255 characters']);
        if(!$request->ajax()):
            Crud::store($this->repository, $request->all(),$this->createMessage,$this->createErrorMessage);
            return \Redirect::to($this->defaultUrl );
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif; 
    }

    

    /**
     * Show the form for editing the specified resource.
     * @param object $Measurement
     * @return Renderable
     */
    public function edit(Measurements $Measurement)
    {
//        ActivityLogHelper::log(
//            \Auth::guard(master_guard)->user()->id,
//            new Measurements(), ['key' => 'edit'],
//            'Measurements', 'You have checked the edit page of measurements for  edit <b>'.$Measurement->name.'</b>',
//            $Measurement
//        );
        return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Measurements',        "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'Measurement'  =>$Measurement
        ]); 
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param object $Measurement
     * @return Renderable
     */
    public function update(Request $request, Measurements $Measurement)
    {
        $request->validate([
            'name'=>'required|max:255',
            'short_code'=> ["required","max:255",function ($attribute, $value, $fail) use($Measurement)
                {
                    if($value):
                        $short_code = Measurements::withTrashed()->where('short_code',$value)->where('id','!=',$Measurement->id)->first();
                        if($short_code):
                            $fail('The :attribute has already been taken.');  
                        endif;
                    endif;

                }
            ], 
            "status"     =>  "required|numeric",  
        ],['short_code.required'=>'The short code field is required.','short_code.max'=>'The short code must not be greater than 255 characters']);
        if(!$request->ajax()):
            Crud::update($this->repository, $Measurement,$request->all(),$this->updateMessage,$this->updateErrorMessage);
            return \Redirect::to($this->defaultUrl );
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif; 
    }

    /**
     * Remove the specified resource from storage.
     * @param object $Measurement
     * @return Renderable
     */
    public function destroy(Measurements $Measurement)
    {
        return Crud::destroy(
            $this->repository, $Measurement,$this->deleteMessage,$this->deleteErrorMessage
        );
    }
}
