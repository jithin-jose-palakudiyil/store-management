<?php

namespace Modules\Master\Http\Controllers\Extras;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Module;
use Modules\Master\Repositories\CommonRepository;
use \View; use \Modules\Master\Helpers\Crud;

class ModuleController extends Controller
{
    protected $repository;
    public function __construct(Module $Module)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('extras.module');
        $this->createUrl            =   route('extras.module.create');  
        $this->createMessage        =   'Module is created successfully.';
        $this->createErrorMessage   =   'Module is not created successfully.';
        $this->updateMessage        =   'Module is updated successfully.';
        $this->updateErrorMessage   =   'Module is not updated successfully.';
        $this->deleteMessage        =   'Module is deleted successfully.';
        $this->deleteErrorMessage   =   'Module is not deleted successfully.';  
        $this->repository           =   new CommonRepository($Module); 
        $this->page_title           =   'Module';
        $this->ViewBasePath         =   'master::extras.authorization.module.';
        View::share('active', 'module');
        
    }
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        return \DataTables::of(Module::latest())->make(true);   
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
                [ "title" => 'Modules',             "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'CreateBtn'     =>  ['url'=>$this->createUrl,'btn_txt'=>'Module'],
            'page_title'    =>  $this->page_title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Module $Module)
    {
        return view($this->ViewBasePath.'create', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
                [ "title" => 'Modules',          "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',           "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' creating',
            'module'        =>  $Module
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
            "status"     =>  "required|numeric",  
        ]);
        if(!$request->ajax()):
            Crud::store($this->repository, $request->all(),$this->createMessage,$this->createErrorMessage);
            return \Redirect::back();
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif; 
    }

    
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Module $Module)
    {
        return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Module',        "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'module'        =>  $Module
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Module $Module)
    {
        $request->validate([
            'name'      =>  'required|max:255', 
            "status"    =>  "required|numeric",  
        ]);
        if(!$request->ajax()):
            Crud::update($this->repository, $Module,$request->all(),$this->updateMessage,$this->updateErrorMessage);
            return \Redirect::to(route('extras.module'));
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif; 
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Module $Module)
    {
        return Crud::destroy(
            $this->repository, $Module,$this->deleteMessage,$this->deleteErrorMessage
        );
    }
}
