<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">
            @if(\Route::getCurrentRoute()->getActionMethod() == 'create')
                Add New 
            @endif
            Calibration</h5>
    </div>
    <?php 
        $LastDate =$Calibration->hasManyCalibrationDates->sortByDesc('id')->where('status',1)->first();
        $next_date = $Calibration->hasManyCalibrationDates->sortByDesc('id')->where('status',0)->first();
        $next_date = (isset($next_date->date)) ? $next_date->date : null;
//        dd($Calibration->hasOneBatchItem);
    ?>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="name">Item Name <span class="text-danger">*</span></label>
                    <input <?php echo (isset($edit) && $edit) ? 'disabled=""':''; ?> type="text" class="form-control autocomplete" id="item_name" name="item_name"  placeholder="Enter Item Name" value="{{(isset($Calibration->hasOneBatchItem->hasOneItem->name) && $Calibration->hasOneBatchItem->hasOneItem->name) ? $Calibration->hasOneBatchItem->hasOneItem->name:old('item_name')}}" >
                    @if($errors->has('item_name'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('item_name') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="item_id">Unique Id<span class="text-danger">*</span></label>
                    <input <?php echo (isset($edit) && $edit) ? 'disabled=""':''; ?> type="text" readonly="" class="form-control" id="unique_id" name="unique_id"  placeholder="Item Id" value="{{(isset($Calibration->hasOneBatchItem->unique_id) && $Calibration->hasOneBatchItem->unique_id) ? $Calibration->hasOneBatchItem->unique_id:''}}" >
                    <input type="hidden" readonly="" class="form-control" id="item_id" name="item_id"  placeholder="Item Id" value="{{(isset($Calibration->hasOneBatchItem->id) && $Calibration->hasOneBatchItem->id) ? $Calibration->hasOneBatchItem->id:''}}" >
                    @if($errors->has('item_id'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('item_id') }}</div>
                    @endif
                </div> 
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label>Calibration Type: <span class="text-danger">*</span></label>
                    <select  <?php echo (isset($edit) && $edit) ? 'disabled=""':''; ?> name="calibration_type_id" id="calibration_type_id" data-placeholder="Calibration Type" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <?php 
                        $CalibrationType = Modules\Master\Entities\CalibrationType::where('status',1)->get();
                        foreach ($CalibrationType as $key => $value):
                            $selected =null; 
                            if(isset($Calibration->calibration_type_id) && $Calibration->calibration_type_id==$value->id):
                                $selected ='selected=""';
                            endif;
                            ?> <option {{$selected}} value="{{$value->id}}">{{$value->name}}</option> <?php
                        endforeach;
                        ?>
                    </select>
                    <div id="calibration_type_id_err">
                        @if($errors->has('calibration_type_id'))
                            <div   class="validation-error-label" style="display: inline-block;">{{ $errors->first('calibration_type_id') }}</div>
                        @endif
                    </div>
                </div>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="next_date">Next date calibration <span class="text-danger">*</span></label>
                    <input type="text" <?php echo (isset($edit) && $edit) ? 'disabled=""':''; ?> class="form-control datepicker-menus" id="next_date" name="next_date"  placeholder="Next date calibration" value="{{(isset($next_date) && $next_date) ? $next_date:''}}" >
                    @if($errors->has('next_date'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('next_date') }}</div>
                    @endif
                </div> 
            </div>
             <div class="col-md-4">
                <div class="form-group ">
                    <label for="days">Days</label>
                    <input type="text" class="form-control" disabled="" id="days" name="days"  placeholder="Days" value="{{(isset($Calibration['days']) && $Calibration['days']) ? $Calibration['days']:old('days')}}" >
                    @if($errors->has('days'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('days') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label >Last date of calibration done</label>
                    <input type="text" disabled="" class="form-control" placeholder="Last date of calibration" value="{{(isset($LastDate['date']) && $LastDate['date']) ? $LastDate['date']:''}}" >
                </div> 
            </div>
        </div>
        <div class="row">
              <div class="col-md-3">
                <div class="form-group ">
                    <label for="calibration_by">Calibration By <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="calibration_by" name="calibration_by"  placeholder="Calibration Name" value="{{(isset($Calibration['calibration_by']) && $Calibration['calibration_by']) ? $Calibration['calibration_by']:old('calibration_by')}}" >
                    @if($errors->has('calibration_by'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('calibration_by') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-3">
                <div class="form-group ">
                    <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number"  placeholder=" Contact Number " value="{{(isset($Calibration['contact_number']) && $Calibration['contact_number']) ? $Calibration['contact_number']:old('contact_number')}}" >
                    @if($errors->has('contact_number'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('contact_number') }}</div>
                    @endif
                </div> 
            </div> 
            <div class="col-md-3">
                <div class="form-group ">
                    <label for="contact_email">Contact Email <span class="text-danger">*</span></label>
                    <input  type="text" class="form-control" id="contact_email" name="contact_email"  placeholder=" Contact Email " value="{{(isset($Calibration['contact_email']) && $Calibration['contact_email']) ? $Calibration['contact_email']:old('contact_email')}}" >
                    @if($errors->has('contact_email'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('contact_email') }}</div>
                    @endif
                </div> 
            </div>
            
           
            <div class="col-md-3">
                <div class="form-group">
                    <label>Status: <span class="text-danger">*</span></label>
                    <select name="status" id="status" data-placeholder="status" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="1"  @if(isset($Calibration['status']) && $Calibration['status']==1) selected @endif >Active</option>
                        <option value="2" @if(isset($Calibration['status']) && $Calibration['status']==2) selected @endif>Inactive</option> 
                     </select>
                    <div id="status_err">
                        @if($errors->has('status'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                </div>
            </div> 
        </div>
       
        <div class="row">  
            <div class="col-md-12 ">
                <button type="submit" class="btn btn-primary pull-right" style="margin-left: 10px">Submit</button> 
            </div>
        </div>
    </div>
</div>
@section('css')
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
@stop 
@section('js') 
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <!--<script src="{{asset('Modules/BackEnd/Resources/assets/validation/jquery.validate.file.js')}}" type="text/javascript"></script>-->
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('Modules/Master/Resources/assets/js/others/calibration.js')}}"></script>  
     <?php if(isset($Calibration->calibration_type_id) && isset($Calibration->id)): ?>
        <script type="text/javascript"> 
            
    $(function() { calibration_type({{$Calibration->calibration_type_id}});});  </script> 
    <?php endif; ?>
@stop