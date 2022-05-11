<?php

namespace Modules\Master\Http\Controllers\Stock;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Store;
use Modules\Master\Repositories\CommonRepository;
use \View; use Modules\Master\Helpers\Crud;
use \Exception;

class StoreController extends Controller
{
        public function __construct(Store $Store)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('stock.store');
        $this->createUrl            =   route('stock.store.create');  
        $this->createMessage        =   'Store is created successfully.';
        $this->createErrorMessage   =   'Store is not created successfully.';
        $this->updateMessage        =   'Store is updated successfully.';
        $this->updateErrorMessage   =   'Store is not updated successfully.';
        $this->deleteMessage        =   'Store is deleted successfully.';
        $this->deleteErrorMessage   =   'Store is not deleted successfully.'; 
        $this->page_title           =   'Store';
        $this->ViewBasePath         =   'master::stock.store.';
        $this->repository           =   new CommonRepository($Store); 
        View::share('active', 'store'); 
        
        $this->middleware('module_permission:store-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:store-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:store-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:store-delete', ['only' => ['destroy']]); 
    
    }
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        return \DataTables::of(Store::latest())->make(true);   
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
                [ "title" => 'Store',               "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','store-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Store'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','store-edit')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','store-delete')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'page_title'    =>  $this->page_title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Store $Store)
    {
        return view($this->ViewBasePath.'create', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',            "url" =>   $this->dashboardUrl ],
                [ "title" => 'Store',               "url" =>   $this->defaultUrl, ],
                [ "title" => 'Create',               "url" =>   'javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'     =>  $this->page_title .' creating',
            'Store'          =>  $Store
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
            
            'name'=> ["required","max:255",function ($attribute, $value, $fail) 
                {
                    if($value):
                        $store_name = Store::withTrashed()->where('name',$value)->first();
                        if($store_name):
                            $fail('The :attribute has already been taken.');  
                        endif;
                    endif;

                }
            ],
                    
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
    public function edit(Store $Store)
    {
       
        return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Items',               "url" =>  $this->defaultUrl, ],
                [ "title" => 'Store',               "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'Store'         =>  $Store
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Store $Store)
    {   
        $request->validate([ 
            'name'=> ["required","max:255",function ($attribute, $value, $fail) use($Store)
                {
                    if($value):
                        $store_name = Store::withTrashed()->where('name',$value)->where('id','!=',$Store->id)->first();
                        if($store_name):
                            $fail('The :attribute has already been taken.');  
                        endif;
                    endif;

                }
            ],
                    
            "status"     =>  "required|numeric",  
        ]);
        if(!$request->ajax()):
            Crud::update($this->repository, $Store,$request->all(),$this->updateMessage,$this->updateErrorMessage);
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
    public function destroy(Store $Store)
    {
        return Crud::destroy(
            $this->repository, $Store,$this->deleteMessage,$this->deleteErrorMessage
        );
    }
}
