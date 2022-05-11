<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">
            @if(\Route::getCurrentRoute()->getActionMethod() == 'create')
                Add 
            @endif
            Breakage / Breakdown </h5>
    </div>
    <?php 
    $disabled = null; $names =null; $item_name=null;
    
    if(isset($Breakage['id']) ): 
        $disabled = 'disabled=""'; 
        if($Breakage->is_responsible==0):
            $names ='Student';
        elseif($Breakage->is_responsible==1):
            $names ='Lab incharge';
        endif; 
        $PivotStoreItems = Modules\Master\Entities\PivotStoreItems::with('hasOneBatch')->where('id',$Breakage->pivot_store_item_id)->withTrashed()->first();
        if($PivotStoreItems ):
         $Items = Modules\Master\Entities\Items::where('id',$PivotStoreItems->hasOneBatch->item_id)->first();
           if($Items):
               $item_name=$Items->name.' - '.$PivotStoreItems->hasOneBatch->unique_id;
           endif;
        endif;
        
    endif;
    
    ?>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>What Is ? <span class="text-danger">*</span></label>
                    <select {{$disabled}} name="what_is" id="what_is" data-placeholder="what is" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="breakage"  @if(isset($Breakage['what_is']) && $Breakage['what_is']=='breakage') selected @endif >Breakage </option>
                        <option value="breakdown" @if(isset($Breakage['what_is']) && $Breakage['what_is']=='breakdown') selected @endif>Breakdown	</option> 
                     </select>
                    <div id="is_responsible_err">
                        @if($errors->has('is_responsible'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('is_responsible') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group ">
                    
                    <label for="unique_id">Unique ID <span class="text-danger">*</span></label>
                    <input {{$disabled}}  type="text" class="form-control autocomplete" id="unique_id" name="unique_id"  placeholder="Enter unique id" value="{{(isset($item_name) && $item_name) ? $item_name:old('unique_id')}}" >
                    <input {{$disabled}} type="hidden" class="form-control" id="pivot_id"  name="pivot_id" value="{{(isset($Breakage['pivot_store_item_id']) && $Breakage['pivot_store_item_id']) ? $Breakage['pivot_store_item_id']:old('pivot_id')}}" >
                    @if($errors->has('unique_id'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('unique_id') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Is responsible ? <span class="text-danger">*</span></label>
                    <select {{$disabled}} name="is_responsible" id="is_responsible" data-placeholder="is responsible" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="0"  @if(isset($Breakage['is_responsible']) && $Breakage['is_responsible']==0) selected @endif >Student</option>
                        <option value="1" @if(isset($Breakage['is_responsible']) && $Breakage['is_responsible']==1) selected @endif>Lab incharge	</option> 
                     </select>
                    <div id="is_responsible_err">
                        @if($errors->has('is_responsible'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('is_responsible') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group ">
                    <label for="breakage_date">Date<span class="text-danger">*</span></label>
                    <input {{$disabled}} type="text" class="form-control datepicker-menus" readonly="" id="breakage_date" name="breakage_date"  placeholder="Enter date" value="{{(isset($Breakage['breakage_date']) && $Breakage['breakage_date']) ? $Breakage['breakage_date']:old('breakage_date')}}" >
                    @if($errors->has('breakage_date'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('breakage_date') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-3">
                <div class="form-group ">
                    <label for="price">Price<span class="text-danger">*</span></label>
                    <input {{$disabled}} type="text" class="form-control" id="price" name="price" readonly="" placeholder="Enter price" value="{{(isset($Breakage['price']) && $Breakage['price']) ? $Breakage['price']:old('price')}}" >
                    @if($errors->has('price'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('price') }}</div>
                    @endif
                </div> 
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-12">
                <div class="form-group ">
                    <label for="comments">Comments</label>
                    <textarea {{$disabled}} style="resize: none;height: 100px" type="text" class="form-control" id="comments" name="comments"  placeholder="comments" value="" >{{(isset($Breakage['comments']) && $Breakage['comments']) ? $Breakage['comments']:old('comments')}}</textarea>
                    @if($errors->has('comments'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('comments') }}</div>
                    @endif
                </div> 
            </div>
           
            
        </div>
        <?php 
        if(\Route::getCurrentRoute()->getActionMethod() != 'create'):
            $disableM = $disablS='';

            if(\Auth::guard(master_guard)->user()->role == 'store'):
                $disableM ='disabled=""';  
            endif;
            if(\Auth::guard(master_guard)->user()->role == 'master'):
                $disablS ='disabled=""';  
                if($Breakage->step !=0):
                    $disableM ='disabled=""';  
                endif;
            endif;
            if($Breakage->step !=1 && \Auth::guard(master_guard)->user()->role == 'store'):
                $disablS ='disabled=""';  
            endif;
        ?>
             
         <div class="col-md-3" >
                <div class="form-group">
                    <label>Action <span class="text-danger">*</span></label>
                    <select   name="status" {{$disableM}} id="status" data-placeholder="status" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="1"  @if(isset($Breakage['status']) && $Breakage['status']==1) selected @endif >Collect payment</option>
                        <option value="2" @if(isset($Breakage['status']) && $Breakage['status']==2) selected @endif>Replace Item</option> 
                        <option value="3" @if(isset($Breakage['status']) && $Breakage['status']==3) selected @endif>Maintenance Item</option> 
                     </select>
                    <div id="status_err">
                        @if($errors->has('status'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                </div>
            </div>
       
            <div class="col-md-3" >
                <div class="form-group">
                    <label>Status of {{ isset($Breakage['what_is']) ? $Breakage['what_is'] : "--------" }}<span class="text-danger">*</span></label>
                    <select   name="step" {{$disablS}}  id="step" data-placeholder="status" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="2"  @if(isset($Breakage['step']) && $Breakage['step']==2) selected @endif >close</option> 
                        <!--<option value="3" @if(isset($Breakage['step']) && $Breakage['step']==3) selected @endif>rejected</option>-->
                        <option value="4" @if(isset($Breakage['step']) && $Breakage['step']==4) selected @endif>permanently damaged</option>
                     </select>
                    <div id="step_err">
                        @if($errors->has('step'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('step') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        
            
         <?php endif; ?>
        <?php 
        if( isset($Breakage['step']) && $Breakage['step'] ==4 ):
            $Breakage_dis ='';
            if(\Auth::guard(master_guard)->user()->role != 'master'):
                $Breakage_dis='disabled="" ';
            endif;
            if($Breakage->is_permanently !=0):
                $Breakage_dis='disabled="" ';
            endif;
            ?>
            <div class="col-md-3" >
                <div class="form-group">
                    <label>Action  of authority for permanently damaged<span class="text-danger">*</span></label>
                    <select   name="is_permanently" {{$Breakage_dis}}  id="is_permanently" data-placeholder="Action  of authority for permanently damaged" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="1"  @if(isset($Breakage['is_permanently']) && $Breakage['is_permanently']==1) selected @endif >approved </option> 
                        <option value="2" @if(isset($Breakage['is_permanently']) && $Breakage['is_permanently']==2) selected @endif>rejected</option>
                         
                     </select>
                    <div id="is_permanently_err">
                        @if($errors->has('is_permanently'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('is_permanently') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        <?php  endif; ?>
    </div>
</div>


@if($errors->any())
    {!! implode('', $errors->all('<div class="validation-error-label">:message</div>')) !!}
@endif

<?php  if(!isset($Breakage['id']) ):?>  
    <div class="panel panel-flat Information" <?php  if(!isset($Breakage['id']) ):?> style="display: none" <?php endif; ?>>
        <div class="panel-heading">
            <h5 class="panel-title"  >   
                <span class="res_inf"></span> Information<button  style="margin-top: -5px;"  type="button" class="pull-right btn border-primary  text-primary-300 btn-flat btn-icon btn-rounded add_item">
                    <i class="  icon-plus3"></i>
                </button> 
            </h5>    
        </div>
        <hr  style="padding: 0px;margin: 0px"/>
        <div class="panel-body">
            <div class="table">
                 <div class="table_row" id="table_row_1">
                     <div class="row" style="border-bottom: 1px solid #ddd;padding-top: 15px"> 
                         <div class="col-md-3">
                            <div class="form-group ">
                                <label><span class="res_inf"></span> Name<span class="text-danger">*</span></label>
                                <input type="text"   class="form-control name"   name="name[1]">
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="form-group ">
                                <label><span class="res_inf"></span> ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control _id"  name="_id[1]"  >

                            </div> 
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group ">
                                <label>Contact Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control contact_number" name="contact_number[1]"  >
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="form-group ">
                                <label>Batch<span class="text-danger lab">*</span></label>
                                <input type="text" class="form-control batch lab_r"  name="batch[1]" >
                            </div> 
                        </div>
                         <div class="col-md-2">
                            <div class="form-group ">
                                <label>Class<span class="text-danger lab">*</span></label>
                                <input type="text" class="form-control class lab_r"  name="class[1]" >
                            </div> 
                        </div>
                        <div class="col-md-1">
                            <div class="remove_item" data-row="1" style="display: none" >  
                                <button style="margin-top: 25px" type="button" class="btn border-warning text-warning-300 btn-flat btn-icon btn-rounded " ><i class="icon-cross2"></i></button> 
                            </div>
                        </div>

                    </div> 
                </div>
            </div>

        </div>
    </div>
<?php 
    else: 
    
       $PivotBreakage =  \Modules\Master\Entities\PivotBreakage::where('breakage_id',$Breakage->id)->get();

?>
<div class="panel panel-flat Information" <?php  if(!isset($Breakage['id']) ):?> style="display: none" <?php endif; ?>>
        <div class="panel-heading">
            <h5 class="panel-title"  >   
                <span class="res_inf">{{$names}}</span> Information 
            </h5>    
        </div>
        <hr  style="padding: 0px;margin: 0px"/>
        <div class="panel-body">
            <div class="table">
                <?php foreach ($PivotBreakage as $key => $value):  
                    $ID ='';
                    if($Breakage->is_responsible==0):
                        $ID =$value->student_id;
                    elseif($Breakage->is_responsible==1):
                        $ID =$value->employee_id;
                    endif;
                    ?>
                    
                
                 <div class="table_row" id="table_row_1">
                     <div class="row" style="border-bottom: 1px solid #ddd;padding-top: 15px"> 
                         <div class="col-md-3">
                            <div class="form-group ">
                                <label><span class="res_inf">{{$names}}</span> Name<span class="text-danger">*</span></label>
                                <input type="text" {{$disabled}}  class="form-control name"   value="{{$value->name}}">
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="form-group ">
                                <label><span class="res_inf">{{$names}}</span> ID <span class="text-danger">*</span></label>
                                <input type="text" {{$disabled}} class="form-control _id"  value="{{$ID}}"  >

                            </div> 
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group ">
                                <label>Contact Number <span class="text-danger">*</span></label>
                                <input type="text" {{$disabled}} class="form-control contact_number" value="{{$value->contact_number}}"  >
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="form-group ">
                                <label>Batch<span class="text-danger">*</span></label>
                                <input type="text" {{$disabled}} class="form-control batch"  value="{{$value->batch}}" >
                            </div> 
                        </div>
                         <div class="col-md-2">
                            <div class="form-group ">
                                <label>Class<span class="text-danger">*</span></label>
                                <input type="text" {{$disabled}} class="form-control class"  value="{{$value->class}}" >
                            </div> 
                        </div> 

                    </div> 
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
<?php endif; ?>

 <?php 
 
        $style_button='';
        if( isset($Breakage['step']) && (\Auth::guard(master_guard)->user()->role == 'master' && ($Breakage['step'] ==1 || $Breakage['step'] ==2 || $Breakage['step'] ==3) )):  
            $style_button ='style="display: none"';
        endif;
        
       if($Breakage->step ==2 || $Breakage->step ==3):
             $style_button ='style="display: none"';
       endif;
       if($Breakage->is_status ==1):
           $style_button ='style="display: none"';
       endif;
       
        
 ?>
        <div class="row" {!!$style_button!!}>  
            <div class="col-md-12 ">
                <button type="submit" class="btn btn-primary pull-right" style="margin-left: 10px">Submit</button> 
            </div>
        </div>
@section('js')
<style>
/*     input[type="text"]:disabled {
  background: #ef3636;color: #fff;
}*/
     .ui-autocomplete {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: none;
  float: left;
  min-width: 160px;
  padding: 5px 0;
  margin: 2px 0 0;
  list-style: none;
  font-size: 14px;
  text-align: left;
  background-color: #ffffff;
  border: 1px solid #cccccc;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 4px;
  -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  background-clip: padding-box;
}

.ui-autocomplete > li > div {
  display: block;
  padding: 3px 20px;
  clear: both;
  font-weight: normal;
  line-height: 1.42857143;
  color: #333333;
  white-space: nowrap;
}

.ui-state-hover,
.ui-state-active,
.ui-state-focus {
  text-decoration: none;
  color: #262626;
  background-color: #f5f5f5;
  cursor: pointer;
}

.ui-helper-hidden-accessible {
  border: 0;
  clip: rect(0 0 0 0);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute;
  width: 1px;
}

 </style>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <!--<script src="{{asset('Modules/BackEnd/Resources/assets/validation/jquery.validate.file.js')}}" type="text/javascript"></script>-->
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('Modules/Master/Resources/assets/js/breakage/breakage.js')}}"></script> 
@stop