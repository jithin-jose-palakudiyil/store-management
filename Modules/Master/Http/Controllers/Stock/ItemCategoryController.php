<?php

namespace Modules\Master\Http\Controllers\Stock;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\ItemCategory;
use Modules\Master\Repositories\CommonRepository;
use \View; use Modules\Master\Helpers\Crud;
use \Exception;

class ItemCategoryController extends Controller
{
       public function __construct(ItemCategory $ItemCategory)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('stock.item-category');
        $this->createUrl            =   route('stock.item-category.create');  
        $this->createMessage        =   'Item Category is created successfully.';
        $this->createErrorMessage   =   'Item Category is not created successfully.';
        $this->updateMessage        =   'Item Category is updated successfully.';
        $this->updateErrorMessage   =   'Item Category is not updated successfully.';
        $this->deleteMessage        =   'Item Category is deleted successfully.';
        $this->deleteErrorMessage   =   'Item Category is not deleted successfully.'; 
        $this->page_title           =   'Item Category';
        $this->ViewBasePath         =   'master::stock.item_category.';
        $this->repository           =   new CommonRepository($ItemCategory); 
        View::share('active', 'item_category'); 
        
        $this->middleware('module_permission:item-category-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:item-category-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:item-category-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:item-category-delete', ['only' => ['destroy']]); 
    
    }
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        return \DataTables::of(ItemCategory::latest())->make(true);   
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
                [ "title" => 'Item Category',       "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','item-category-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Item Category'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','item-category-edit')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','item-category-delete')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
            'page_title'    =>  $this->page_title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(ItemCategory $ItemCategory)
    {
         return view($this->ViewBasePath.'create', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',            "url" =>   $this->dashboardUrl ],
                [ "title" => 'Item Category',        "url" =>   $this->defaultUrl, ],
                [ "title" => 'Create',               "url" =>   'javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'     =>  $this->page_title .' creating',
            'ItemCategory'   =>  $ItemCategory
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
            'measurement_id'=>'required|array|min:1',
            'name' => ["required","max:255",function ($attribute, $value, $fail) 
                {
                    if($value):
                        $name = ItemCategory::withTrashed()->where('name',$value)->first();
                        if($name): $fail('The :attribute has already been taken.'); endif;
                    endif;
                }
            ],  "status"        =>  "required|numeric",  
        ]);
        if(!$request->ajax()):
            $data = $request->all(); $measurement_id = $request->measurement_id; unset($data['measurement_id']);
            $crud = Crud::store($this->repository, $data,$this->createMessage,$this->createErrorMessage);
            if($crud['error'] ==null && $crud['response']):  $error =null;
                try{ $response =$crud['response']; $response->belongsToMeasurements()->sync($measurement_id);   } catch (Exception $ex) {$error=$ex->getMessage();}
            endif;
            if($error !=null): session()->flash('flash-error-message','Measurement unit is not allotted !<br/> '.$error); endif;
            return \Redirect::to($this->defaultUrl );
        else: return response()->json(['message' => 'Page not found!'], 404); endif;
    }

     

    /**
     * Show the form for editing the specified resource.
     * @param int $Id
     * @return Renderable
     */
    public function edit(ItemCategory $ItemCategory)
    {
        
        $ItemCategory = ItemCategory::with('belongsToManyMeasurements')->where('id',$ItemCategory->id)->get()->first();
    
        return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Item Category',       "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'ItemCategory'  =>  $ItemCategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, ItemCategory $ItemCategory)
    {
        $request->validate([
            'measurement_id'=>'required|array|min:1',
            'name'          => ["required","max:255",function ($attribute, $value, $fail) use($ItemCategory)
                {
                    if($value):
                        $name = ItemCategory::withTrashed()->where('id','!=',$ItemCategory->id)->where('name',$value)->first();
                        if($name): $fail('The :attribute has already been taken.');   endif;
                    endif;
                }
            ],  "status"        =>  "required|numeric",  
        ]);
        if(!$request->ajax()): 
            $data = $request->all(); $measurement_id = $request->measurement_id; unset($data['measurement_id']);
            if(!$request->exists('allow_usage')): $data['allow_usage']=2; endif;
            $crud = Crud::update($this->repository, $ItemCategory,$data,$this->updateMessage,$this->updateErrorMessage);
            if($crud['error'] ==null):  $error =null;
                try{  $ItemCategory->belongsToMeasurements()->sync($measurement_id);   } catch (Exception $ex) {$error=$ex->getMessage();}
            endif;
            if($error !=null): session()->flash('flash-error-message','Measurement unit is not allotted !<br/> '.$error); endif;
            return \Redirect::to($this->defaultUrl );
        else:  return response()->json(['message' => 'Page not found!'], 404); endif; 
        
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ItemCategory $ItemCategory)
    {
        return Crud::destroy(
            $this->repository, $ItemCategory,$this->deleteMessage,$this->deleteErrorMessage
        );
    }
    
    
   
}
