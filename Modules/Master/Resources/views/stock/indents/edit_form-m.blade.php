<div class="panel panel-flat"> 
    <div class="panel-body">
        <div class="row">
        <div class="col-md-2">
            <div class="form-group ">
                <label>Indent Id </label> 
                <input type="text" disabled="" class="form-control"  value="{{$Indent->id}}" >
            </div> 
        </div>
        <div class="col-md-2">
            <div class="form-group ">
                <label>Date </label>
                <input type="text" disabled="" class="form-control"  value="{{$Indent->date}}" >
            </div> 
        </div>
        <div class="col-md-2">
            <div class="form-group ">
                <label>Request From </label>
                <?php 
                $From='';  
                if($Indent->request_from==null && $Indent->from_warehouse==1):
                    $From='WareHouse';
                else:
                    $store1 = \Modules\Master\Entities\Store::where('id',$Indent->request_from)->first();
                    $From=isset($store1->name) ? $store1->name :'';
                endif;
                ?>
                <input type="text" disabled="" class="form-control"  value="{{$From}}" >
            </div> 
        </div>
        <div class="col-md-2">
            <div class="form-group ">
                <label>Request To </label>
                 <?php 
                $To='';  
                if($Indent->request_to==null && $Indent->to_warehouse==1):
                    $To='WareHouse';
                else:
                    $store2 = \Modules\Master\Entities\Store::where('id',$Indent->request_to)->first();
                    $To=isset($store2->name) ? $store2->name :'';
                endif;
                ?>
                <input type="text" disabled="" class="form-control"  value="{{$To}}" >
            </div> 
        </div>
        <div class="col-md-2">
                <div class="form-group ">
                    <label>User </label>
                    <?php  
                    $User = \Modules\Master\Entities\Auth::where('id',$Indent->requested_user)->first();
                    $User=isset($User->name) ? $User->name :'';
                 
                ?>
                    <input type="text" disabled="" class="form-control"  value="{{$User}}" >
                </div> 
            </div> 
        </div>
    </div>   
</div>
<div id="errors">
    @if($errors->any())
        {!! implode('', $errors->all('<div class="validation-error-label">:message</div>')) !!}
    @endif

</div>
 
<?php if($Indent->request_to==null && $Indent->to_warehouse ==1): ?>
  {!! Form::model($Indent, ['method' => 'PATCH', 'route' => ['stock.indents.update', $Indent->id],'class'=>'form-valide','id'=>'IndentItems','enctype'=>'multipart/form-data']) !!}     
<?php else: ?>
 {!! Form::model($Indent, ['method' => 'PATCH', 'route' => ['stock.indents_store_action', $Indent->id],'class'=>'form-valide','id'=>'IndentItems','enctype'=>'multipart/form-data']) !!}     
 
<?php endif; ?>
   
    {{ csrf_field() }}
    <div class="panel panel-flat"> 
        <div class="panel-body">
             
            <?php  
            $hasManyIndentItems = $Indent->hasManyIndentItems->all();
            if(!empty($hasManyIndentItems)):
                foreach ($hasManyIndentItems as $key => $value):
                    $readonly = '';
            
                    $Item= null; $AvailableQty = $ApprovedQty = 0; $batch_items =null; $filtered = $StoreItemsList = [];
                    if($Indent->request_to == null && $Indent->to_warehouse==1): 
                        $Item = Modules\Master\Entities\Items::with('hasOneMeasurements:id,short_code')->where("category_id",$value->category_id) ->where('id',$value->item_id)->first();   
                         
                    elseif($Indent->request_to != null && $Indent->to_warehouse==0): 
                        $Item = Modules\Master\Entities\Items::
                            with('hasOneMeasurements:id,short_code')
                            ->select('items.id','items.has_unique_id','items.name','store_items_list.quantity as quantity','items.category_id','items.measurement_id')
                            ->join('store_items_list','store_items_list.item_id','=', 'items.id') 
                            ->where("items.category_id",$value->category_id) 
                            ->where('store_items_list.store_id',$Indent->request_to)
                            ->where('items.id',$value->item_id)
                            ->first();  
                    endif;
                    $AvailableQty = isset($Item->quantity) ? $Item->quantity : 0;
                    
                    if(isset($Item->has_unique_id) && $Item->has_unique_id==1):
                        
                        $readonly = 'readonly="" '; 
                        //request from store to wherehouse 
                        if($Indent->request_to == null && $Indent->to_warehouse==1):
                            
                           $batch_items = \Modules\Master\Entities\BatchItems::
                                select('batch_items.id','batch_items.unique_id', 'batch_items.item_id')
                                ->join('items','items.id','=', 'batch_items.item_id') 
                                ->where("items.has_unique_id",1) 
                                ->where("batch_items.item_id",$Item->id) 
                                ->whereNotIn('batch_items.id', function ($query) {
                                    $query->select('pivot_store_items.batch_item_id')->from('pivot_store_items')->where("pivot_store_items.is_recived",'!=',2);
                                })
                               ->get();
                        elseif($Indent->request_to != null && $Indent->to_warehouse==0):
                              
                            $batch_items = \Modules\Master\Entities\PivotStoreItems::
                                select('batch_items.*')
                                ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id') 
                                ->join('items','items.id','=', 'batch_items.item_id') 
                                ->where("items.has_unique_id",1) 
                                ->where("pivot_store_items.is_recived",1)
                                ->whereIn('store_item_id', function ($query) use($Indent,$value) {
                                    $query->select('store_items_list.id')->from('store_items_list') 
                                            ->where("store_items_list.store_id",$Indent->request_to)
                                            ->where("store_items_list.item_id",$value->item_id) ;
                                           
                                })->get();
                               
                        endif;
                         
                    endif;
                    
        
                     
                $filtered  =(isset($batch_items) && !empty($batch_items)) ? $batch_items->pluck('unique_id','id')->toArray(): [];  
                $Item_name= isset($Item->name) ? $Item->name.' - '.$Item->id : '';
                $Unit= isset($Item->hasOneMeasurements->short_code) ? $Item->hasOneMeasurements->short_code : '';
             
                
               ?>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label>Item Name </label> 
                            <input type="text" disabled=""  class="form-control"  value="{{isset($Item_name) ? $Item_name : ''}}" >
                            <input type="hidden" name="_id[{{$key}}]"  class="form-control"  value="{{$value->id}}" >
                        </div> 
                    </div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label>Available Qty </label> 
                            <input type="text" disabled="" class="form-control"  value="{{isset($AvailableQty) ? $AvailableQty : ''}}" id="available_qty_{{$key}}" >
                        </div> 
                    </div> 
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label>Requested Qty </label> 
                            <input type="text" disabled="" class="form-control"  value="{{$value->requested_quantity}}" id="requested_qty_{{$key}}">
                        </div> 
                    </div> 
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label>Unit </label> 
                            <input type="text" disabled="" class="form-control"  value="{{isset($Unit) ? $Unit : ''}}" >
                        </div> 
                    </div> 
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label>Approved Qty <span class="text-danger">*</span></label> 
                            <input type="text" {{$readonly}} class="form-control approved_qty" data-qty='{{$key}}' name="approved_qty[{{$key}}]" id="approved_qty_{{$key}}" data-id="{{$key}}" value="{{isset($ApprovedQty) ? $ApprovedQty : ''}}" >
                        </div> 
                    </div> 
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status: <span class="text-danger">*</span></label>
                            <select name="status[{{$key}}]" id="status_{{$key}}" data-placeholder="status" class="select status" data-minimum-results-for-search="-1">
                                <option></option> 
                                <option value="1">Approve</option>
                                <option value="2" >Reject</option> 
                             </select> 
                            <div id="err_status_{{$key}}" ></div>
                        </div>
                    </div> 
                </div>
            <?php
          
                if(isset($filtered) && !empty($filtered)):
                    ?><div class="row"><?php
                    foreach ($filtered as $keys => $value) :
                    ?>
                    <div class="col-md-2 ">
                        <div class="form-group"> 
                             <label class="checkbox-inline">
                                <input type="checkbox" class="control-primary batch_item batch_item_key_{{$key}}" data-key="{{$key}}" name="batch_item[{{$key}}][]" value="{{$keys}}">
                                {{$value}}
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                  <hr/>  
                
                <?php
               endforeach;
           endif;
            ?>
          
        </div>   
    </div>
    <div class="row">  
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right" style="margin-left: 10px">Submit</button> 
        </div>
    </div>
  {!! Form::close() !!} 
@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <!--<script src="{{asset('Modules/BackEnd/Resources/assets/validation/jquery.validate.file.js')}}" type="text/javascript"></script>-->
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('Modules/Master/Resources/assets/js/stock/indents.js')}}"></script> 
@stop