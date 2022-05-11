<?php

namespace Modules\Master\Http\Controllers\Others;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\LicenceRenewal;
use Modules\Master\Repositories\CommonRepository;
use \View; use \Modules\Master\Helpers\Crud;


class LicenceRenewalController extends Controller
{
    protected $repository;
    public function __construct(LicenceRenewal $LicenceRenewal)
    {   
        $this->dashboardUrl         =   route('master_dashboard');
        $this->defaultUrl           =   route('others.licence-renewal');
        $this->createUrl            =   route('others.licence-renewal.create');  
        $this->createMessage        =   'Licence Renewal is created successfully.';
        $this->createErrorMessage   =   'Licence Renewal is not created successfully.';
        $this->updateMessage        =   'Licence Renewal is updated successfully.';
        $this->updateErrorMessage   =   'Licence Renewal is not updated successfully.';
        $this->deleteMessage        =   'Licence Renewal is deleted successfully.';
        $this->deleteErrorMessage   =   'Licence Renewal is not deleted successfully.';  
        $this->repository           =   new CommonRepository($LicenceRenewal); 
        $this->page_title           =   'Licence Renewal';
        $this->ViewBasePath         =   'master::others.licence-renewal.';
        View::share('active', 'licence-renewal');
        
        $this->middleware('module_permission:licence-renewal-list', ['only' => ['index','GetDataTableList']]);
        $this->middleware('module_permission:licence-renewal-create', ['only' => ['create','store']]);
        $this->middleware('module_permission:licence-renewal-edit', ['only' => ['edit','update']]);
        $this->middleware('module_permission:licence-renewal-delete', ['only' => ['destroy']]); 
    
    }
    
       /**
     * Api for DataTable listing of the resource.
     * @return DataTables
     */
    public function GetDataTableList()
    {
         $query = LicenceRenewal::
                with(['hasOneBatchItem' => function ($query) {
                    $query->select('id', 'item_id','unique_id');
                    $query->with('hasOneItem:id,name');
                }])  
                ->latest();
                
        return \DataTables::of($query)->make(true);   
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
                [ "title" => 'Licence Renewal',     "url" =>  $this->defaultUrl, "active" => 1 ]
            ],
            'page_title'    =>  $this->page_title,
            'CreateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','licence-renewal-create')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? ['url'=>$this->createUrl,'btn_txt'=>'Licence Renewal'] : null,
            'editBtn'       => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','licence-renewal-edit')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'deleteBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','licence-renewal-delete')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
            'updateBtn'     => ( (\Auth::guard(master_guard)->user()->belongsToManyPermissions->where('slug','licence-renewal-upload')->first() || \Auth::guard(master_guard)->user()->is_developer==1) ) ? true : false,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(LicenceRenewal $LicenceRenewal)
    {
         return view($this->ViewBasePath.'create', [
            'breadcrumb'    => [ 
                [ "title" => 'Dashboard',        "url" => $this->dashboardUrl ],
                [ "title" => 'Licence Renewal',   "url" =>  $this->defaultUrl, ],
                [ "title" => 'Create',           "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' creating',
            'LicenceRenewal'   =>  $LicenceRenewal
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
              
             
            'item_name'=>'required|max:255', 
            'item_id'=> [ "required","numeric",function ($attribute, $value, $fail)  {
                if($value):
                    $item = \Modules\Master\Entities\Items::where('items.status',1) 
                        ->join('batch_items','batch_items.item_id','=', 'items.id')
                        ->where('batch_items.id',$value)->first();
                    if(!$item): $fail('This item not found !.');   endif;
                endif; }
            ],  
            'licence_no' => 'required|max:255',
            'expiry_date' => 'required',
            'name'=>'required|max:255',
            'contact_number' => 'required',
            'contact_email' => 'required|email',
        ]);
        $data=[ 'batch_item_id' => $request->item_id, 'licence_no' => $request->licence_no, 'expiry_date' => $request->expiry_date, 'name' => $request->name, 'contact_number' => $request->contact_number, 'contact_email' => $request->contact_email ];
        if(!$request->ajax()):
            Crud::store($this->repository, $data,$this->createMessage,$this->createErrorMessage);
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
    public function edit(LicenceRenewal $LicenceRenewal)
    {
        $LicenceRenewal = $LicenceRenewal->with(['hasOneBatchItem' => function ($query) {
                    $query->select('id', 'item_id','unique_id');
                    $query->with('hasOneItem:id,name');
                }])->where('id',$LicenceRenewal->id)->first();
       return view($this->ViewBasePath.'edit', [
            'breadcrumb'        => [ 
                [ "title" => 'Dashboard',           "url" => $this->dashboardUrl ],
                [ "title" => 'Licence Renewal',        "url" =>  $this->defaultUrl, ],
                [ "title" => 'Edit',                "url" =>  ' javascript:void(0)', "active" => 1 ]
            ], 
            'page_title'    =>  $this->page_title .' editing',
            'LicenceRenewal'        =>  $LicenceRenewal,  'edit'=>true
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, LicenceRenewal $LicenceRenewal)
    {
        
         $request->validate([
              
             
            'item_name'=>'max:255', 
            'item_id'=> [ "required","numeric",function ($attribute, $value, $fail)  {
                if($value):
                    $item = \Modules\Master\Entities\Items::where('items.status',1)
                        ->join('batch_items','batch_items.item_id','=', 'items.id')
                        ->where('batch_items.id',$value)->first();
                    if(!$item): $fail('This item not found !.');   endif;
                endif; }
            ],  
            'licence_no' => 'required|max:255',
            'expiry_date' => 'required',
            'name'=>'required|max:255',
            'contact_number' => 'required',
            'contact_email' => 'required|email',
        ]);
        $data=[ 'batch_item_id' => $request->item_id, 'licence_no' => $request->licence_no, 'expiry_date' => $request->expiry_date, 'name' => $request->name, 'contact_number' => $request->contact_number, 'contact_email' => $request->contact_email ];
        if(!$request->ajax()):
            Crud::update($this->repository, $LicenceRenewal,$data,$this->updateMessage,$this->updateErrorMessage);
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
    public function destroy(LicenceRenewal $LicenceRenewal)
    {
         return Crud::destroy(
            $this->repository, $LicenceRenewal,$this->deleteMessage,$this->deleteErrorMessage
        );
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ItemAutocomplete(Request $request)
    {
//        $res = \Modules\Master\Entities\Items::select("name","id")
//                ->where("name","LIKE","%{$request->term}%")
//                ->where("status",1)
//                ->get();
     $res = \Modules\Master\Entities\Items::select("items.name","batch_items.id","batch_items.unique_id",
               "batch_items.unique_id AS unique_ids",
             
                    \DB::raw(
                                "IF(
                                    (
                                        select count(*) as cnt from licence_renewal
                                        INNER JOIN batch_items on batch_items.id = licence_renewal.batch_item_id
                                        WHERE batch_items.unique_id =unique_ids
                                    ) >= 1,true,false 
                                ) as has_licence_renewal"
                            )
             )
                ->join('batch_items','batch_items.item_id','=', 'items.id')     
                ->where("items.name","LIKE","%{$request->term}%")
                ->where("items.status",1)
                ->get();
        return response()->json($res);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getUpdateModel(Request $request, $id)
    {   
        if( $request->ajax()):
            $html =  $this->getForm( $id);
            return response()->json(['html' => $html]);
        else:  abort(404); endif;
         
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getForm($id)
    {
         
        $LicenceRenewal = LicenceRenewal::with('hasOneItem:id,name')->where('id',$id)->first(); 
        $html =  \View::make($this->ViewBasePath.'partials.master', compact('LicenceRenewal'))->render();
        return $html;
    }
    
    
     /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function statusUpdate(Request $request){
        $error = $html = null; 
        $request->validate
           ([ 
                'LicenceRenewal_id'=> ["required","numeric",function ($attribute, $value, $fail){
                    if($value):
                        $item = LicenceRenewal::where('status',0)->where('id',$value)->first();
                        if(!$item): $fail('The item has not found for licence renewal');   endif;
                    endif; 
                }], 
                "status"     =>  "required|numeric",  
                "renewed_date"     =>  "required",  
        ]);     
            try { 
                $data = ['comments'=>$request->comments,'status'=>$request->status,'renewed_date'=>$request->renewed_date];
                $LicenceRenewal= LicenceRenewal::where('status',0)->where('id',$request->LicenceRenewal_id)->first();
                if($LicenceRenewal):
                    $LicenceRenewal->update($data);
                    $html =  \View::make($this->ViewBasePath.'partials.statusUpdateForm', compact('LicenceRenewal'))->render();
                endif; 
            } catch (Exception $ex) { $error = $ex->getMessage(); }
            
            if($error == null): 
                return response()->json(['sucess' => true,'html' => $html,'message'=>'<div class="alert alert-success no-border">
                                                                             <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                                                             <span class="text-semibold">Well done!</span> You successfully updated.
                                                                 </div>']); 
            else: 
                return response()->json(['sucess' => false,'html' => $html,'message'=>'<div class="alert alert-danger alert-bordered">
                                                                             <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                                                                             <span class="text-semibold">Oh snap!</span> Change a few things up and try submitting again.
                                                                 </div>']);  
            endif;
         
    }
    
    
}
