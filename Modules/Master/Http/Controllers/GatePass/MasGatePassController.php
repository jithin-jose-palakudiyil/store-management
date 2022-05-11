<?php

namespace Modules\Master\Http\Controllers\GatePass;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\GatePass;
use Modules\Master\Repositories\CommonRepository;
use \View; use \Modules\Master\Helpers\Crud;
use \Exception; use PDF;

class MasGatePassController extends Controller
{
     protected $repository;
    public function __construct(GatePass $GatePass)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('gate-pass-m');
        $this->createUrl            =   route('gate-pass-m.create');  
        $this->createMessage        =   'Gate Pass is created successfully.';
        $this->createErrorMessage   =   'Gate Pass is not created successfully.';
        $this->updateMessage        =   'Gate Pass is updated successfully.';
        $this->updateErrorMessage   =   'Gate Pass is not updated successfully.';
        $this->deleteMessage        =   'Gate Pass is deleted successfully.';
        $this->deleteErrorMessage   =   'Gate Pass is not deleted successfully.';  
        $this->repository           =   new CommonRepository($GatePass); 
        $this->page_title           =   'Gate Pass';
        $this->ViewBasePath         =   'master::gate-pass.m.';
        View::share('active', 'gate-pass');
        
        $this->middleware('module_permission:gate-pass-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:gate-pass-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:gate-pass-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:gate-pass-delete', ['only' => ['destroy']]); 
    
    }
    
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList(Request $request)
    {
        $GatePass = [];
//        dd($request->breakage);
        if($request->exists('breakage')):
            $query = \Modules\Master\Entities\Breakage::
                select('breakage.id','breakage.step','breakage.breakage_date','items.name','batch_items.unique_id')
                    ->join('batch_items','batch_items.id','=', 'breakage.batch_item_id')
                    ->join('items','items.id','=', 'batch_items.item_id')
                    ->where('breakage.id',$request->breakage); 
            $breakage =$query->first(); 
            if($breakage):
               $GatePass= GatePass::where('breakage_id',$breakage->id)->latest();
            endif;              
        endif;
        return \DataTables::of($GatePass)->make(true);   
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if($request->exists('breakage')):
                $query = \Modules\Master\Entities\Breakage::
                            select('breakage.*','batch_items.unique_id') 
                            ->join('batch_items','batch_items.id','=', 'breakage.batch_item_id')
                            ->where('breakage.id',$request->breakage)
                            ->where('breakage.batch_item_id','!=',null); 
                        
            $breakage  =  $query->first(); 
            if($breakage): 
                return view($this->ViewBasePath.'index', [
                    'breadcrumb'    =>  [ 
                        [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                        [ "title" => 'Gate Pass for '.$breakage->name.' - '.$breakage->unique_id, "url" =>  $this->defaultUrl.'?breakage='.$breakage->id, "active" => 1 ]
                    ],
                    'CreateBtn'     =>  ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','gate-pass-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1)  ) ? ['url'=>$this->createUrl.'?breakage='.$breakage->id,'btn_txt'=>'Gate Pass'] : null,
                    'editBtn'       =>  ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','gate-pass-edit')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
                    'deleteBtn'     =>  ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','gate-pass-delete')->first()|| \Auth::guard(master_guard)->user()->is_developer==1)) ? true : false,
                    'page_title'    =>  $this->page_title.' for '.$breakage->unique_id,
                    'breakage'      =>  $breakage
                ]);
            endif;
        endif;
        
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(GatePass $GatePass,Request $request)
    {
        if($request->exists('breakage')):
            $breakage = \Modules\Master\Entities\Breakage::
                select('breakage.*','batch_items.unique_id','items.name') 
                ->join('batch_items','batch_items.id','=', 'breakage.batch_item_id') 
                ->join('items','items.id','=', 'batch_items.item_id')
                ->where('breakage.batch_item_id','!=',null)
                ->where('breakage.id',$request->breakage)->where('breakage.step',1)
               
                ->first(); 
            if($breakage): 
                    return view($this->ViewBasePath.'create', [
                        'breadcrumb'    => [ 
                            [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
                            [ "title" => 'Gate Pass for '.$breakage->name.' - '.$breakage->unique_id,     "url" =>  $this->defaultUrl.'?breakage='.$breakage->id, ],
                            [ "title" => 'Create',           "url" =>  ' javascript:void(0)', "active" => 1 ]
                        ], 
                        'page_title'    =>  'Gate Pass for '.$breakage->name.' - '.$breakage->unique_id.' creating',
                        'breakage'   =>  $breakage,
                        'GatePass'   =>  $GatePass,
                    ]);
            endif;
        endif;
        
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
       if($request->exists('breakage')):
            $breakage = \Modules\Master\Entities\Breakage::
               select('breakage.item_id','breakage.id','breakage.step','breakage.breakage_date','items.name','batch_items.unique_id')
                        ->join('items','items.id','=', 'breakage.item_id') 
                        ->join('batch_items','batch_items.id','=', 'breakage.batch_item_id')
                        ->where('breakage.id',$request->breakage)->where('breakage.step',1)
                        ->first(); 
            if($breakage): 
                $request->validate([  "supplier_id"     =>  "required|numeric", 
                    "pass_date"     =>  "required",
                    'name'=>'required|max:255', 
                    'contact_number'=>'required|numeric|digits_between:10,12', 
                    'email'=>'required|max:255|email',    ]);
                $data =[ "breakage_id" => $breakage->id,   "supplier_id" => $request->supplier_id, "pass_date" => $request->pass_date, "name" => $request->name, "contact_number" => $request->contact_number, "email" =>  $request->email, "purpose" => $request->purpose ];
                if(!$request->ajax()):
                    Crud::store($this->repository, $data,$this->createMessage,$this->createErrorMessage);
                    return \Redirect::to($this->defaultUrl .'?breakage='.$breakage->id);
                else:
                    return response()->json(['message' => 'Page not found!'], 404);
                endif;   
            endif;
        endif;
        
        abort(404);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(GatePass $GatePass,Request $request)
    {
        
        $breakage = \Modules\Master\Entities\Breakage::
                select('breakage.batch_item_id','breakage.item_id','breakage.id','breakage.step','breakage.breakage_date','items.name','batch_items.unique_id')
                        
                       
                        ->join('batch_items','batch_items.id','=', 'breakage.batch_item_id')
                ->join('items','items.id','=', 'batch_items.item_id')
                ->where('breakage.id',$GatePass->breakage_id)->first(); 
         
          
            if($breakage): 
                    return view($this->ViewBasePath.'show', [
                        'breadcrumb'    => [ 
                            [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
                            [ "title" => 'Gate Pass for '.$breakage->name.' - '.$breakage->unique_id,     "url" =>  $this->defaultUrl.'?breakage='.$breakage->id, ],
                            [ "title" => 'Create',           "url" =>  ' javascript:void(0)', "active" => 1 ]
                        ], 
                        'page_title'    =>  'Gate Pass for '.$breakage->name.' - '.$breakage->unique_id.' creating',
                        'breakage'   =>  $breakage,
                        'GatePass'   =>  $GatePass,
                        'show'=>TRUE
                    ]);
            endif;
        
        
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(GatePass $GatePass)
    {
            $data = [
                   
                   'GatePass'=>$GatePass,
                     ];
         
         
         $view_blade =$this->ViewBasePath.'pass';
         return view($view_blade, compact('GatePass'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, GatePass $GatePass)
    {
        
        if($request->exists('breakage')):
                $breakage = \Modules\Master\Entities\Breakage::
                select('breakage.id','breakage.step','breakage.breakage_date','items.name','batch_items.unique_id') 
                ->join('batch_items','batch_items.id','=', 'breakage.batch_item_id')
                ->join('items','items.id','=', 'batch_items.item_id')
                ->where('breakage.id',$GatePass->breakage_id)
                ->where('breakage.step',1)->orWhere('breakage.step',2)
                ->orWhere('breakage.step',3)->first(); 
            if($breakage):  
                $request->validate([ "is_breakage"     =>  "required|numeric",]);
                $data =[  "is_breakage" => $request->is_breakage, "status" => 1, "comments" => $request->exists('comments') ? $request->comments : null, ];
                if(!$request->ajax()): 
                    Crud::update($this->repository, $GatePass,$data,$this->updateMessage,$this->updateErrorMessage);
                    return \Redirect::to($this->defaultUrl .'?breakage='.$GatePass->breakage_id );
                else:
                    return response()->json(['message' => 'Page not found!'], 404);
                endif;
            endif;
        endif;
        
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
