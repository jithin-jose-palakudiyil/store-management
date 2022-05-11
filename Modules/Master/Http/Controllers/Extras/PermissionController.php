<?php

namespace Modules\Master\Http\Controllers\Extras;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Master\Repositories\CommonRepository;
use \View; use \Modules\Master\Helpers\Crud;
use \Modules\Master\Entities\Permission;
 
class PermissionController extends Controller
{
    protected $repository;
    public function __construct(Permission $permission)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('extras.permissions');
        $this->createUrl            =   route('extras.permissions.create');  
        $this->createMessage        =   'Permission is created successfully.';
        $this->createErrorMessage   =   'Permission is not created successfully.';
        $this->updateMessage        =   'Permission is updated successfully.';
        $this->updateErrorMessage   =   'Permission is not updated successfully.';
        $this->deleteMessage        =   'Permission is deleted successfully.';
        $this->deleteErrorMessage   =   'Permission is not deleted successfully.';  
        $this->repository           =   new CommonRepository($permission); 
        $this->page_title           =   'Permission';
        $this->ViewBasePath         =   'master::extras.authorization.permissions.';
        View::share('active', 'permission');
        
    }
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        return \DataTables::of(Permission::with('hasModule')->latest())->make(true);   
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
         return view($this->ViewBasePath.'index', [
            'breadcrumb'    =>  [ 
                [ "title" => 'Dashboard',      "url" => $this->dashboardUrl ],
                [ "title" => 'Permissions',    "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'CreateBtn'     =>  ['url'=>$this->createUrl,'btn_txt'=>'Permissions'],
            'page_title'    =>  $this->page_title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Permission $permission)
    {
        return view($this->ViewBasePath.'create', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Permissions',         "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',              "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' creating',
            'permission'    =>  $permission,
            'modules'       => \Modules\Master\Entities\Module::all(),    
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
            'name'          =>  'required|max:255', 
            "module_id"     =>  "required|numeric",  
            "status"        =>  "required|numeric",  
        ]);
        if(!$request->ajax()):
            Crud::store($this->repository, $request->all(),$this->createMessage,$this->createErrorMessage);
            return \Redirect::to($this->defaultUrl);
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif; 
    }

   

    /**
     * Show the form for editing the specified resource.
     * @param object $permission
     * @return Renderable
     */
    public function edit(Permission $permission)
    { 
        return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',            "url" => $this->dashboardUrl ],
                [ "title" => 'Permission',           "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                 "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'permission'    =>$permission,
            'modules'       => \Modules\Master\Entities\Module::all(), 
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param object $permission
     * @return Renderable
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name'          =>  'required|max:255', 
            "module_id"     =>  "required|numeric",  
            "status"        =>  "required|numeric",  
        ]);
         if(!$request->ajax()):
            Crud::update($this->repository, $permission,$request->all(),$this->updateMessage,$this->updateErrorMessage);
            return \Redirect::to($this->defaultUrl);
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif; 
    }

    /**
     * Remove the specified resource from storage.
     * @param object $permission
     * @return Renderable
     */
    public function destroy(Permission $permission)
    {
        return Crud::destroy(
            $this->repository, $permission,$this->deleteMessage,$this->deleteErrorMessage
        );
    }
    
}
