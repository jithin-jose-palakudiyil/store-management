<?php

namespace Modules\Master\Http\Controllers\Extras;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Suppliers;
use Modules\Master\Repositories\CommonRepository;
use \View; use \Modules\Master\Helpers\Crud;

class SuppliersController extends Controller
{
    protected $repository;
    public function __construct(Suppliers $Supplier)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('extras.suppliers');
        $this->createUrl            =   route('extras.suppliers.create');  
        $this->createMessage        =   'Supplier is created successfully.';
        $this->createErrorMessage   =   'Supplier is not created successfully.';
        $this->updateMessage        =   'Supplier is updated successfully.';
        $this->updateErrorMessage   =   'Supplier is not updated successfully.';
        $this->deleteMessage        =   'Supplier is deleted successfully.';
        $this->deleteErrorMessage   =   'Supplier is not deleted successfully.';  
        $this->repository           =   new CommonRepository($Supplier); 
        $this->page_title           =   'Suppliers';
        $this->ViewBasePath         =   'master::extras.suppliers.';
        View::share('active', 'suppliers');
        
        $this->middleware('module_permission:suppliers-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:suppliers-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:suppliers-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:suppliers-delete', ['only' => ['destroy']]); 
    
    }
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        return \DataTables::of(Suppliers::latest())->make(true);   
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
                [ "title" => 'Suppliers',           "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'page_title'    =>  $this->page_title,
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','suppliers-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Suppliers'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','suppliers-edit')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','suppliers-delete')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Suppliers $Supplier)
    {
        return view($this->ViewBasePath.'create', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
                [ "title" => 'Suppliers',     "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',           "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' creating',
            'Supplier'   =>  $Supplier
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
            'phone'=>'required|numeric|digits_between:10,12',
            'email'=>'required|max:255|email',
            'address'=>'required',
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
     * @param int $id
     * @return Renderable
     */
    public function edit(Suppliers $Supplier)
    {
       return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Suppliers',           "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'Supplier'      =>  $Supplier
        ]); 
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Suppliers $Supplier)
    {
        $request->validate([
            'name'=>'required|max:255',
            'phone'=>'required|numeric|digits_between:10,12',
            'email'=>'required|max:255|email',
            'address'=>'required',
            "status"     =>  "required|numeric",  
        ]);
        if(!$request->ajax()):
            Crud::update($this->repository, $Supplier,$request->all(),$this->updateMessage,$this->updateErrorMessage);
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
    public function destroy(Suppliers $Supplier)
    {
        return Crud::destroy(
            $this->repository, $Supplier,$this->deleteMessage,$this->deleteErrorMessage
        );
    }
}
