<?php

namespace Modules\Master\Http\Controllers\Breakage;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Breakage;
use Modules\Master\Repositories\CommonRepository;
use \View; use \Modules\Master\Helpers\Crud;
use \Exception;

class MasBreakageController extends Controller
{
    
    public function __construct(Breakage $Breakage)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('breakage-m');
        $this->createUrl            =   route('breakage-m.create');  
        $this->createMessage        =   'Breakage is created successfully.';
        $this->createErrorMessage   =   'Breakage is not created successfully.';
        $this->updateMessage        =   'Breakage is updated successfully.';
        $this->updateErrorMessage   =   'Breakage is not updated successfully.';
        $this->deleteMessage        =   'Breakage is deleted successfully.';
        $this->deleteErrorMessage   =   'Breakage is not deleted successfully.';  
        $this->repository           =   new CommonRepository($Breakage); 
        $this->page_title           =   'Breakage / Breakdown';
        $this->ViewBasePath         =   'master::breakage.m';
        View::share('active', 'breakage-m');
        
         
        $this->middleware('module_permission:breakage-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:breakage-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:breakage-delete', ['only' => ['destroy']]); 
    
    }
    
    
    /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList(Request $request)
    {
        $query = Breakage::select('breakage.is_status','breakage.id','breakage.step','breakage.breakage_date','items.name','batch_items.unique_id','breakage.is_permanently')
                ->join('items','items.id','=', 'breakage.item_id') 
                ->join('batch_items','batch_items.id','=', 'breakage.batch_item_id')
                ->where('breakage.batch_item_id','!=',null); 
        $breakage = $query->latest('breakage.created_at'); 
        return \DataTables::of($breakage)->make(true);   
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $array = 
        [
            'breadcrumb'    =>  [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Breakage / Breakdown',    "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'page_title'    =>  $this->page_title,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','breakage-edit')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','breakage-delete')->first() || \Auth::guard(master_guard)->user()->is_developer==1) && \Auth::guard(master_guard)->user()->role =='master' ) ? true : false,
            'gatePassBtn'   => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','gate-pass-list')->first() || \Auth::guard(master_guard)->user()->is_developer==1) || \Auth::guard(master_guard)->user()->role =='master' ) ? true : false,
        ];
        
        if( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','breakage-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) && \Auth::guard(master_guard)->user()->role =='master' ):
            $array['CreateBtn']=['url'=>$this->createUrl,'btn_txt'=>'Breakage / Breakdown'];
        endif;
        return view($this->ViewBasePath.'.index',$array );
        
       
    }
 
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Breakage $Breakage)
    {
        if(\Auth::guard(master_guard)->user()->role =='master'): 
         return view($this->ViewBasePath.'.create', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',               "url" =>  $this->dashboardUrl ],
                [ "title" => 'Breakage / Breakdown',    "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',                  "url" =>  'javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' creating',
            'Breakage'   =>  $Breakage
        ]); 
        else: abort(404); endif;
    }

    
    
    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
       
       $request->validate([
                'unique_id' => 'required', 
                'what_is' => 'required', 
                'batch_item_id' => 'required|numeric',
                'is_responsible' => 'required|numeric',
                'breakage_date' => 'required',
                'price' => 'required|numeric', 
            ]);
        
        if(!$request->ajax()): 
           
            
            $BatchItems = \Modules\Master\Entities\BatchItems::find($request->batch_item_id);
            if($BatchItems):
                $Items = \Modules\Master\Entities\Items::find($BatchItems->item_id);
                $BatchItems->update(['whs_breakage'=>1]);
                $Items->update(['quantity'=>($Items->quantity-1)]);
                $data=
                    [
                        'what_is' =>$request->what_is, 'batch_item_id' =>$request->batch_item_id,'item_id'=>$Items->id,
                        'is_responsible' =>$request->is_responsible, 'breakage_date' =>$request->breakage_date,
                        'price' =>$request->price, 'comments' =>$request->comments,
                    ]; 
            
               
                
                $Crud = Crud::store($this->repository, $data,$this->createMessage,$this->createErrorMessage);
                if($Crud['response'] !=null && $Crud['error']==null ):
                    if($request->name !=null || $request->_id !=null || $request->contact_number ):
                        $breakage = $Crud['response']; 
                        $pivot =[
                           'employee_id' =>$request->_id,
                           'name' =>$request->name,
                           'contact_number' =>$request->contact_number,
                           'breakage_id' => $breakage->id
                        ]; 
                        if(!empty($pivot)):
                         \Modules\Master\Entities\PivotBreakage::insert($pivot);
                        endif;
                    endif; 
                endif; 
                 return \Redirect::to($this->defaultUrl);
            else: return abort(404);  endif;
        else:
            return response()->json(['message' => 'Page not found!'], 404);
        endif;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Breakage $Breakage)
    {
        return view($this->ViewBasePath.'.edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Breakage / Breakdown',            "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'Breakage'      =>  $Breakage
        ]); 
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Breakage $Breakage)
    {
//        dd($Breakage);
        $data = [];
        if($Breakage['step']==0):
            $request->validate([ 'status' => 'required|numeric', ]);
            $data=['status'=>$request->status,'step'=>1];
        elseif($Breakage['step']==1):
            $request->validate([ 'step' => 'required|numeric', ]);
            $data=['step'=>$request->step];
            if($request->step==2): 
                $data['is_status']=1;
            endif; 
        elseif( isset($Breakage['step']) && $Breakage['step'] ==4 ):  
            $request->validate([  "is_permanently"     =>  "required",   ],['is_permanently.required'=>'The Action of authority for permanently damaged field is required.']);
            $data=['is_permanently'=>$request->is_permanently];
            $data['is_status']=1;
            
        endif;
        $_Breakage = \Modules\Master\Entities\Breakage::with('hasOneItem')->where('id',$Breakage->id)->first();
        if(!$request->ajax() && $_Breakage->hasOneItem):
            $Crud =  Crud::update($this->repository, $Breakage,$data,$this->updateMessage,$this->updateErrorMessage);
            if($Crud['error']==null && $Crud['response']):
                if($request->exists('step') && $request->step==2): 
                    $Items = $_Breakage->hasOneItem;
                    $Items->update(['quantity'=>($Items->quantity+1)]);
                endif;
                
                 if(isset($data['is_status']) && $data['is_status']==1): 
                    $BatchItems = \Modules\Master\Entities\BatchItems::where('id',$Breakage->batch_item_id)->first();
                    if($BatchItems):
                        $BatchItems->update(['whs_breakage'=>0]); 
                    endif; 
                endif;
                
                if( $request->exists('is_permanently') && isset($Breakage['step']) && $Breakage['step'] ==4): 
                    if($request->is_permanently==2):
                        $Items = $_Breakage->hasOneItem;
                        $Items->update(['quantity'=>($Items->quantity+1)]);
                    elseif($request->is_permanently==1): 
                        \Modules\Master\Entities\BatchItems::where('id',$Breakage->batch_item_id)->update(['whs_breakage'=>1,'deleted_at'=> \Carbon\Carbon::now()]);
                    endif;
                endif;
                
               
            endif;
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
    public function destroy($id)
    {
        //
    }
}
