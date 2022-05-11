@extends('master::layouts.master')
@section('content') 
 
<div class="panel panel-flat"> 
    <div class="panel-body">
        <div class="row"> 
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Date <span class="text-danger">*</span></label>
                    <input type="text" disabled="" class="form-control"  value="<?=$indents->date;?>" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Request From: <span class="text-danger">*</span></label>
                    <?php
                     if($indents->from_warehouse!=1):
                       $store1 = \Modules\Master\Entities\Store::where('id','=',$indents->request_from)->first();  
                     ?>    <input type="text" disabled="" class="form-control"  value="{{ (isset($store1->name)) ? $store1->name : '' }}" >
                    <?php else: ?>
                      <input type="text" disabled="" class="form-control"  value="WareHouse" >
                    <?php endif; ?>
                      
                      
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Request To: <span class="text-danger">*</span></label>
                     
                     <?php
                     if($indents->to_warehouse!=1):
                       $store2 = \Modules\Master\Entities\Store::where('id','=',$indents->request_to)->first();  
                     ?>    <input type="text" disabled="" class="form-control"  value="{{ (isset($store2->name)) ? $store2->name : '' }}" >
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
                    <textarea  class="form-control" disabled=""  name="comments" style="resize: none;height: 100px"><?=$indents->comments;?></textarea>
                </div> 
            </div>
        </div>
    </div> 
</div>
<?php 
 
$hasManyIndentItems = $indents->hasManyIndentItems->all(); 
if(!empty($hasManyIndentItems)):  
?> 
<?php   if( ( $indents->request_from != \Auth::guard(master_guard)->user()->store_id) &&  $indents->authority_status==1 && $indents->to_status==0 && $indents->to_warehouse!=1 && \Auth::guard(master_guard)->user()->role=='store'): ?>
<!--{!! Form::model($indents, ['method' => 'PATCH', 'route' => ['stock.store_update', $indents->id],'class'=>'form-valide','id'=>'store_update','enctype'=>'multipart/form-data']) !!}-->     
<?php endif; ?>

<?php   if($requests->type=='request_sent' && $indents->authority_status==1 && $indents->to_status==1 && $indents->from_status==0  && \Auth::guard(master_guard)->user()->role=='store'): ?>
{!! Form::model($indents, ['method' => 'PATCH', 'route' => ['stock.store_from_update', $indents->id],'class'=>'form-valide','id'=>'store_from_update','enctype'=>'multipart/form-data']) !!}     
<?php endif; ?>

<div class="panel panel-flat ItemInformation" >
    <div class="panel-heading">
        <h5 class="panel-title"  >   
            <span>Item Information </span>  
        </h5>  
        <div class="row">
                <div class="col-md-2 ">
                 <div class="form-group"> 
                       <label class="checkbox-inline">
                          <input type="checkbox" checked="" disabled="" class="control-primary"   >
                          Approved
                     </label>
                 </div>
             </div>
            <div class="col-md-2 ">
                 <div class="form-group"> 
                       <label class="checkbox-inline">
                          <input type="checkbox" checked="" disabled="" class="control-danger"   >
                          Transferred
                     </label>
                 </div>
             </div>
            <?php if($indents->from_status==1): ?>
                 <div class="col-md-2 ">
                 <div class="form-group"> 
                       <label class="checkbox-inline">
                          <input type="checkbox" checked="" disabled="" class="control-success"   >
                          Received
                     </label>
                 </div>
             </div>
            <?php  endif; ?>
        </div>
    </div>
    <hr  style="padding: 0px;margin: 0px"/>
    <div class="panel-body">
        <div class="table">
            <?php foreach ($hasManyIndentItems as $key => $value) :
                 
//                dd($value);
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
                            <input type="text" disabled="" class="form-control"  value="{{ (isset($value->approved_quantity)) ? $value->approved_quantity : '' }}" >
                        </div> 
                    </div> 
                     <div class="col-md-1">
                        <div class="form-group ">
                            <label>Transfer Qty <span class="text-danger">*</span></label> 
                            <input type="text" disabled="" class="form-control"  value="{{ (isset($value->transferred_qty)) ? $value->transferred_qty : '' }}" >
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
                    <?php // if($indents->authority_status==1 && $indents->from_status==0): ?>
                        <?php $qry  = Modules\Master\Entities\BatchItems::
                                 select('batch_items.*','pivot_store_items.pivot_indent_id as pivot_indent_id','pivot_store_items.id as pivot_store_id','pivot_store_items.is_transferred as is_transferred','pivot_store_items.is_recived as is_recived')
                                ->join('pivot_store_items','pivot_store_items.batch_item_id','=', 'batch_items.id');
//                                if($indents->authority_status ==1 && $indents->to_status ==1):
//                                else:   
//                                endif;
//                                $qry->where("pivot_store_items.is_requested",1)
                                $qry->where('pivot_store_items.pivot_indent_id',$value->id);
                                $has_unique_ids = $qry->get()->all(); 
                                 
                        if(!empty($has_unique_ids)): ?>
                            <?php if(isset($has_unique_ids) && !empty($has_unique_ids)): ?>
                              <div class="row">
                                  <div class="col-md-12 ">
                                         <h6 class="panel-title">Approved items with transferred items: </h6> <br/>
                                     </div>
                                 <?php  
                                 foreach ($has_unique_ids as $keys => $values) :   
                                        $class = 'control-primary'; 
                                        $checked = $disabled = ''; 
                                        if($values->is_transferred==1):
                                            $class = 'control-danger'; 
//                                            $checked = 'checked="" ';
                                        else:
                                            $disabled = 'disabled=""';
//                                            $checked = 'checked="" ';
                                        endif;
                                         if($values->is_recived==1):
                                             
                                             $checked = 'checked="" ';
                                            $class = 'control-success'; 
                                         endif;
                                         if($indents->from_status==1):
                                             $disabled = 'disabled=""';
                                         endif;
                                          
                                        if(\Auth::guard(master_guard)->user()->role =='master'):
                                            $disabled='disabled="" ';
                                        elseif($requests->type=='request_recived' && \Auth::guard(master_guard)->user()->role =='store'):
                                                $disabled='disabled="" '; 
                                        endif;
                                     ?>
                                 <div class="col-md-2 ">
                                     <div class="form-group"> 
                                          <label class="checkbox-inline">
                                              <input type="checkbox" {{$checked}} name="pivot_store_id[{{$values->pivot_indent_id}}][]" value="{{$values->pivot_store_id}}"  {{$disabled}} class="{{$class}}"   >
                                             {{$values->unique_id}}
                                         </label>
                                     </div>
                                 </div>
                                 <?php endforeach; ?>
                                 </div>
                             <?php endif; ?>  
                        <?php endif; ?>
                  <?php 
                  
                  if( ($value->is_transferred==1 || $value->is_transferred==2) && $Item->has_unique_id != 1):   ?>
                 
                        <div class="row">
                            <div class="col-md-2">
                               <div class="form-group"> 
                                   <?php
                                   $disabled_=''; $selected =null;
                                   if($value->is_transferred==2):
                                       $disabled_='disabled="" ';
                                        $selected ='selected=""';
                                   endif;
                                   if($indents->authority_status==1 && $indents->from_status==1):
                                        $disabled_='disabled="" ';
                                        $selected =null;
                                   endif;
                                   
                                   if(\Auth::guard(master_guard)->user()->role =='master'):
                                        $disabled_='disabled="" ';
                                    elseif($requests->type=='request_recived' && \Auth::guard(master_guard)->user()->role =='store'):
                                        $disabled_='disabled="" '; 
                                   endif;
//                                   dd($value->is_recived);
                                   ?>
                                   <label>Is Recived ? :  <span class="text-danger">*</span></label> 
                                    <select {{$disabled_}} class="select is_recived" data-id='{{$value->id}}' name="is_recived[{{$value->id}}]" required=""  data-minimum-results-for-search="-1"> 
                                       <option value="">select</option>
                                       <option value="1"  <?php if(isset($value->is_recived) && $value->is_recived ==1):?> selected="" <?php endif; ?> >Recived </option>
                                       <option value="2" {{$selected}}   <?php if(isset($value->is_recived) && $value->is_recived ==2):?> selected="" <?php endif; ?> >Not Recived </option> 
                                    </select>  
                                   <div id="errr_{{$value->id}}"></div>
                               </div>
                           </div>
                            
                             
                        </div> 
                   <?php endif; ?>
                           
                 <hr/>
            </div>
              
            <?php  endforeach; ?>
        </div>
        
    

    
            
        <?php 
       
        if( $requests->type=='request_sent' && $indents->authority_status==1 && $indents->to_status==1 && \Auth::guard(master_guard)->user()->role=='store'  && $indents->from_status==0): ?>
            <div class="row">  
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right" style="margin-left: 10px">Submit</button> 
                </div>
            </div>
            {!! Form::close() !!}
        <?php endif; ?>
</div>
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