<?php

namespace Modules\Master\Http\Controllers\Extras;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use \Modules\Master\Entities\Auth;
use Modules\Master\Repositories\CommonRepository;
use \View; use \Modules\Master\Helpers\Crud;
use Modules\Master\Helpers\ActivityLogHelper; 
use \Exception;

class UsersController extends Controller
{
    protected $repository;
    public function __construct(Auth $auth)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('extras.users');
        $this->createUrl            =   route('extras.users.create');  
        $this->createMessage        =   'User is created successfully.';
        $this->createErrorMessage   =   'User is not created successfully.';
        $this->updateMessage        =   'User is updated successfully.';
        $this->updateErrorMessage   =   'User is not updated successfully.';
        $this->deleteMessage        =   'User is deleted successfully.';
        $this->deleteErrorMessage   =   'User is not deleted successfully.';  
        $this->repository           =   new CommonRepository($auth); 
        $this->page_title           =   'User';
        $this->ViewBasePath         =   'master::extras.users.';
        View::share('active', 'users');
        
        $this->middleware('module_permission:users-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:users-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:users-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:users-delete', ['only' => ['destroy']]); 
        $this->middleware('module_permission:users-permissions-assign', ['only' => ['module_permissions_index','module_permissions_save']]); 
    
    }
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
        return \DataTables::of(Auth::where('is_developer','!=',1)->latest())->make(true);   
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
                [ "title" => 'Users',               "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','users-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1)  ) ? ['url'=>$this->createUrl,'btn_txt'=>'User'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','users-edit')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','users-delete')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'assigningBtn'  => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','users-permissions-assign')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'page_title'    =>  $this->page_title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Auth $auth)
    {
        return view($this->ViewBasePath.'create', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
                [ "title" => 'Users',            "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',           "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' creating',
            'auth'   =>  $auth
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
            'username'=> ["required","max:255",function ($attribute, $value, $fail) 
                {
                    if($value):
                        $username = Auth::withTrashed()->where('username',$value)->first();
                        if($username): $fail('The :attribute has already been taken.');   endif;
                    endif; 
                }
            ],  
            "status"    =>  "required|numeric",   'password'  =>'required|max:255',
            'confirm_password'=>'required|max:255|same:password',
            "role" =>'required|max:255',
            "store_id" => "required_if:role,==,store"
        ]);
        $data =$request->all();  unset($data['_token']);  unset($data['password']);  unset($data['confirm_password']);
        $data['password'] = bcrypt($request->password); $data['store_id'] = ($request->exists('store_id') && is_numeric($request->store_id)) ? $request->store_id : null;
        
        if(!$request->ajax()):
            Crud::store($this->repository,$data,$this->createMessage,$this->createErrorMessage);
            return \Redirect::to($this->defaultUrl );
        else: return response()->json(['message' => 'Page not found!'], 404); endif;    
    }

    
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
    $auth = Auth::find($id); if(!$auth){ abort (404);}
         ActivityLogHelper::log(
            \Auth::guard(master_guard)->user()->id,
            new Auth(), ['key' => 'edit'],
            'users', 'You have checked the edit page of users for  edit <b>'.$auth->name.'</b>',
            $auth
        );
        return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Users',        "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'auth'  =>$auth
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $auth = Auth::find($id); if(!$auth){ abort (404);} 
        $request->validate([
            'name'=>'required|max:255',
            'username'=> ["required","max:255",function ($attribute, $value, $fail) use($auth)
                {
                    if($value):
                        $username = Auth::withTrashed()->where('username',$value)->where('id','!=',$auth->id)->first();
                        if($username): $fail('The :attribute has already been taken.');   endif;
                    endif; 
                }
            ],  
            "status"    =>  "required|numeric",   'password'  =>'required_without:HdnEdit|max:255',
            'confirm_password'=>'required_without:HdnEdit|max:255|same:password',
            "role" =>'required|max:255',
            "store_id" => "required_if:role,==,store"
        ]);
        $data =$request->all();  unset($data['_token']);  unset($data['password']); unset($data['HdnEdit']);  unset($data['confirm_password']);
        if($request->exists('password') && $request->password !=null):$data['password'] = bcrypt($request->password);endif;
        $data['store_id'] = ($request->exists('store_id') && is_numeric($request->store_id)) ? $request->store_id : null;
        if(!$request->ajax()):
            Crud::update($this->repository, $auth,$data,$this->updateMessage,$this->updateErrorMessage);
            return \Redirect::to($this->defaultUrl);
        else: return response()->json(['message' => 'Page not found!'], 404); endif; 
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {   $auth = Auth::find($id); if(!$auth){ abort (404);} 
        return Crud::destroy(
            $this->repository, $auth,$this->deleteMessage,$this->deleteErrorMessage
        );
    }
    
    
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function module_permissions_index($id) 
    {
        $auth = Auth::with('belongsToManyPermissions')->where('id',$id)->first(); if(!$auth){ abort (404);} 
        
        $query = \Modules\Master\Entities\Module::with('hasManyPermissions');
        if($auth->role=='master'): $query->where('is_master', 1); endif;
        if($auth->role=='store'): $query->where('is_store', 1); endif;
        $module_permissions = $query->get()->all();
        if($auth->id==\Auth::guard(master_guard)->user()->id):
            if($auth->is_developer!=1):
                abort(403,'permission denied to this account');
            endif;
        endif;
        
        return view($this->ViewBasePath.'assigning', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',               "url" => $this->dashboardUrl ],
                [ "title" => 'Users',                   "url" =>  $this->defaultUrl, ],
                [ "title" => 'Assigning permissions',   "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  ' Assigning permissions',
            'module_permissions'=>$module_permissions,
            'auth'=>$auth
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function module_permissions_save(Request $request,$id) 
    {   $error = null;
        $auth = Auth::find($id); if(!$auth){ abort (404);} 
        try{
             $auth->belongsUserHasPermissions()->sync($request->permissions);
        } catch (Exception $ex) {$error=$ex->getMessage(); }    
        if($error == null): 
            session()->flash('flash-success-message','Assigning permissions is completed successfully!');
        else: 
            session()->flash('flash-error-message','Assigning permissions is not successfully completed! </br>'.$error); 
        endif;
        return \Redirect::to($this->defaultUrl);
        
    }
    
    
    public function get_store(Request $request,$role) {
       
        if($request->ajax()):
            $store = \Modules\Master\Entities\Store::where('status',1)->get(); 
            $html =  \View::make($this->ViewBasePath.'partials.store', compact('store','request'))->render();
            return response()->json(['html' => $html], 200);
        else: return response()->json(['html' => 'null'], 200); endif; 
        
    }
}
