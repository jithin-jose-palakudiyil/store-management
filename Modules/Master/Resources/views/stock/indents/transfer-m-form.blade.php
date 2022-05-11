@extends('master::layouts.master')
@section('content') 
 
<div class="panel panel-flat"> 
    <div class="panel-body">
        <div class="row"> 
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Date <span class="text-danger">*</span></label>
                    <input type="text" disabled="" class="form-control"  value="<?=$indent->date;?>" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Request From: <span class="text-danger">*</span></label>
                    <?php  if($indent->from_warehouse!=1): $store1 = \Modules\Master\Entities\Store::where('id','=',$indent->request_from)->first();  ?>
                    <input type="text" disabled="" class="form-control"  value="{{ (isset($store1->name)) ? $store1->name : '' }}" >
                    <?php else: ?>
                      <input type="text" disabled="" class="form-control"  value="Main Store" >
                    <?php endif; ?>
                      
                      
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Request To: <span class="text-danger">*</span></label>
                    <?php if($indent->to_warehouse!=1): $store2 = \Modules\Master\Entities\Store::where('id','=',$indent->request_to)->first(); ?>    <input type="text" disabled="" class="form-control"  value="{{ (isset($store2->name)) ? $store2->name : '' }}" >
                    <?php else: ?>
                    <input type="text" disabled="" class="form-control"  value="Main Store" >
                    <?php endif; ?>   
                </div>
            </div> 
        </div>
        <div class="row">
        <div class="col-md-12">
                <div class="form-group ">
                    <label>Comments</label>
                    <textarea  class="form-control" disabled=""  name="comments" style="resize: none;height: 100px"><?=$indent->comments;?></textarea>
                </div> 
            </div>
        </div>
    </div> 
</div>
<?php 
$hasManyIndentItems = $indent->hasManyIndentItems->all(); 
if(!empty($hasManyIndentItems)):  
?> 
 <div id="errors">
    @if($errors->any())
        {!! implode('', $errors->all('<div class="validation-error-label">:message</div>')) !!}
    @endif

</div>
<?php  if($indent->request_to ==null && $indent->authority_status ==1 && $indent->to_status !=1 && $indent->to_warehouse ==1): ?> 
{!! Form::model($indent, ['method' => 'PATCH', 'route' => ['stock.indents_transfer_action', $indent->id],'class'=>'form-valide','id'=>'indents_transfer_action','enctype'=>'multipart/form-data']) !!}     
<?php endif; ?>    
<div class="panel panel-flat ItemInformation" >
    <div class="panel-heading">
        <h5 class="panel-title"  >   
            <span>Approved Item Information </span>  
        </h5>    
    </div>
    <hr  style="padding: 0px;margin: 0px"/>
    <div class="panel-body">
        <div class="table">
            <?php foreach ($hasManyIndentItems as $key => $value) : 
                ?> 
             <div class="table_row" id="table_row_1">
                 <div class="row" >  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Category: <span class="text-danger">*</span></label>
                            <?php $ItemCategory = Modules\Master\Entities\ItemCategory::where('id',$value->category_id)->first(); ?>
                            <input disabled="" type="text" class="form-control" value="{{ (isset($ItemCategory->name)) ? $ItemCategory->name : '' }}"  > 
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group ">
                            <label>Item <span class="text-danger">*</span></label>
                            <?php $Item = Modules\Master\Entities\Items::with('hasOneMeasurements:id,short_code')->where('id',$value->item_id)->first();   ?>
                            
                            <input type="text" disabled="" class="form-control" value="{{ (isset($Item->name)) ? $Item->name.' - '.$Item->id : '' }}" >
                            
                        </div> 
                    </div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            
                            <label>Unit<span class="text-danger">*</span></label>
                            <input type="text" disabled="" class="form-control unit" value="{{ isset($Item->hasOneMeasurements->short_code) ? $Item->hasOneMeasurements->short_code :'' }}" >
                        </div> 
                    </div>
                     
                    <div class="col-md-1">
                        <div class="form-group ">
                            <label>Qty Request<span class="text-danger">*</span></label>
                            <input type="text" disabled=""  class="form-control"  value="{{ (isset($value->requested_quantity)) ? $value->requested_quantity : '' }}" >
                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div class="form-group ">
                            <label>Approved Qty <span class="text-danger">*</span></label> 
                            <input type="text" disabled="" class="form-control aQty" id="aQty_{{$value->id}}"  value="{{ (isset($value->approved_quantity)) ? $value->approved_quantity : '' }}" >
                        </div> 
                    </div> 
                     <?php
                     $disabled = '';$checked = ''; $Tdisabled= '';
                    
                    if($indent->request_to ==null && $indent->authority_status ==1 && $indent->to_status == 1 && $indent->to_warehouse ==1):
                        $Tdisabled = $disabled = 'disabled="" ';
                        $checked = 'checked="" '; 
                        
                    endif;
                    $Items = \Modules\Master\Entities\Items::find($value->item_id);
                    if($Items && $Items->has_unique_id==1): 
                        $Tdisabled= 'disabled="" ';
                    endif;
                     ?>
                    <div class="col-md-1">
                        <div class="form-group ">
                            <label>Transfer Qty <span class="text-danger">*</span></label> 
                            <input type="number" {{$Tdisabled}} class="form-control tQty" id="tQty_{{$value->id}}" name="tQty[{{$value->id}}]" data-tid="{{$value->id}}"  value="{{ (isset($value->transferred_qty)) ? $value->transferred_qty : '' }}" >
                        </div> 
                    </div> 
<!--                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Authority status: <span class="text-danger">*</span></label> 
                            <select class="select" disabled="" data-minimum-results-for-search="-1"> 
                                <option></option>
                                <option value="1" @If(isset($value['status']) && $value['status']==1) selected @endif >Approve</option>
                                <option value="2" @If(isset($value['status']) && $value['status']==2) selected @endif  >Reject</option> 
                             </select> 
                            
                        </div>
                    </div>-->
                </div>
                    <?php 
                    
                     
                    
                    if($Items && $Items->has_unique_id==1): 
                        if($indent->authority_status==1): ?>
                            <?php $qry = Modules\Master\Entities\BatchItems::
                                    select('batch_items.*','pivot_store_items.id as pivot_store_id','pivot_store_items.is_transferred as is_transferred')
                                    ->join('pivot_store_items','pivot_store_items.batch_item_id','=', 'batch_items.id');
                                    if($indent->request_to ==null && $indent->authority_status ==1 && $indent->to_status ==1 && $indent->to_warehouse ==1):
//                                        $qry->where("pivot_store_items.deleted_at",null)
//                                        ->orWhere("pivot_store_items.deleted_at",'!=',null);
                                    else:
//                                        $qry->where("pivot_store_items.is_requested",1);
                                    endif;
//                                    $qry->where("pivot_store_items.is_requested",1);
                                    $qry->where('pivot_store_items.pivot_indent_id',$value->id);
                                    $has_unique_ids = $qry->get()->all(); 
                            
//                                    
                            if(!empty($has_unique_ids)):   ?>
                                <?php if(isset($has_unique_ids) && !empty($has_unique_ids)): ?>
                                  <div class="row">
                                     <div class="col-md-12 ">
                                         <h6 class="panel-title">Is transferred the approved items? : </h6> <br/>
                                     </div>
                                     <?php foreach ($has_unique_ids as $keys => $values) :
                                        $class = 'control-primary'; 
                                        if($values->is_transferred==1):
                                             $class = 'control-danger'; 
                                        endif;
                                         if($values->is_transferred!=1):
                                             $checked = '" '; 
                                         endif;
                                     ?>
                                     <div class="col-md-2 ">
                                         <div class="form-group"> 
                                              <label class="checkbox-inline">
                                                  <input {{$disabled}} {{$checked}}  type="checkbox" name='approve[{{$value->id}}][{{$values->pivot_store_id}}]' data-cid="{{$value->id}}" class="{{$class}} chk_{{$value->id}} chkAction"   >
                                                 {{$values->unique_id}}
                                             </label>
                                         </div>
                                     </div>
                                     <?php endforeach; ?>
                                     </div>
                                 <?php endif; ?>   
                            <?php endif; ?> 
                       <?php endif; ?>
                    <?php elseif($Items && $Items->has_unique_id==0):   ?>
                 <div class="row">
                    <div class="col-md-12 ">
                        <h6 class="panel-title">Is transferred the approved items? :</h6> <br/>
                    </div>
                    <div class="col-md-3 ">
                        <select class="select is_transferred" {{$disabled}} name="is_transferred[{{$value->id}}]" data-minimum-results-for-search="-1" > 
                           <option value="">select</option>
                           <option value="1" @if(isset($value['is_transferred']) && $value['is_transferred']==1) selected @endif>Transferred</option>
                           <option value="2"@if(isset($value['is_transferred']) && $value['is_transferred']==2) selected @endif >Not Transferred</option> 
                        </select>
                    </div>
                 </div>
                 
                    <?php endif; ?>      
                 <hr/>
            </div>
              
            <?php  endforeach; ?>
        </div> 
    </div>     
</div>
<?php  if($indent->request_to ==null && $indent->authority_status ==1 && $indent->to_status !=1 && $indent->to_warehouse ==1): ?>
    

    <div class="row">  
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right" id="BtnSub" style="margin-left: 10px">Submit</button> 
        </div>
    </div>
{!! Form::close() !!} 
<?php  endif; ?>
<?php endif; ?>


 
@stop
@section('js')
 
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <!--<script src="{{asset('Modules/BackEnd/Resources/assets/validation/jquery.validate.file.js')}}" type="text/javascript"></script>-->
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('Modules/Master/Resources/assets/js/stock/indents.js')}}"></script> 
@stop