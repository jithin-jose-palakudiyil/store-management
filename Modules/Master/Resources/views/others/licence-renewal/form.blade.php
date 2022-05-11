<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">
            @if(\Route::getCurrentRoute()->getActionMethod() == 'create')
                Add New 
            @endif
            Licence Renewal </h5>
    </div>
    
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="name">Item Name <span class="text-danger">*</span></label>
                    <input <?php echo (isset($edit) && $edit) ? 'disabled=""':''; ?> type="text" class="form-control autocomplete" id="item_name" name="item_name"  placeholder="Enter Item Name" value="{{(isset($LicenceRenewal->hasOneBatchItem->hasOneItem->name) && $LicenceRenewal->hasOneBatchItem->hasOneItem->name) ? $LicenceRenewal->hasOneBatchItem->hasOneItem->name:old('item_name')}}" >
                    @if($errors->has('item_name'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('item_name') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="item_id">Unique Id <span class="text-danger">*</span></label>
                    <input <?php echo (isset($edit) && $edit) ? 'disabled=""':''; ?> type="text" readonly="" class="form-control" id="unique_id" name="unique_id"  placeholder="Item / Unique Id" value="{{(isset($LicenceRenewal->hasOneBatchItem->unique_id) && $LicenceRenewal->hasOneBatchItem->unique_id) ? $LicenceRenewal->hasOneBatchItem->unique_id:old('item_id')}}" >
                    <input   type="hidden" readonly="" class="form-control" id="item_id" name="item_id"  placeholder="Item / Unique Id" value="{{(isset($LicenceRenewal->hasOneBatchItem->id) && $LicenceRenewal->hasOneBatchItem->id) ? $LicenceRenewal->hasOneBatchItem->id:old('item_id')}}" >
                    @if($errors->has('item_id'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('item_id') }}</div>
                    @endif
                </div> 
            </div>
            
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="licence_no">Licence Number<span class="text-danger">*</span></label>
                    <input type="text"  class="form-control" id="licence_no" name="licence_no"  placeholder="Licence Number" value="{{(isset($LicenceRenewal->licence_no) && $LicenceRenewal->licence_no) ? $LicenceRenewal->licence_no:old('licence_no')}}" >
                    @if($errors->has('licence_no'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('licence_no') }}</div>
                    @endif
                </div> 
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="expiry_date">Expiry Date<span class="text-danger">*</span></label>
                    <input type="text"  class="form-control datepicker-menus" id="expiry_date" name="expiry_date"  placeholder="expiry date" value="{{(isset($LicenceRenewal->expiry_date) && $LicenceRenewal->expiry_date) ? $LicenceRenewal->expiry_date:old('expiry_date')}}" >
                    @if($errors->has('expiry_date'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('expiry_date') }}</div>
                    @endif
                </div> 
            </div>
             
             
              
             
<!--            <div class="col-md-4">
                <div class="form-group ">
                    <label >Last licence renewed date </label>
                    <input type="text" disabled="" class="form-control" placeholder="Last licence renewed date " value="{{(isset($LastDate['date']) && $LastDate['date']) ? $LastDate['date']:''}}" >
                </div> 
            </div>-->
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number"  placeholder=" Contact Number " value="{{(isset($LicenceRenewal['contact_number']) && $LicenceRenewal['contact_number']) ? $LicenceRenewal['contact_number']:old('contact_number')}}" >
                    @if($errors->has('contact_number'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('contact_number') }}</div>
                    @endif
                </div> 
            </div> 
              <div class="col-md-4">
                <div class="form-group ">
                    <label for="name">Licence renewal incharge <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"  placeholder="Name" value="{{(isset($LicenceRenewal['name']) && $LicenceRenewal['name']) ? $LicenceRenewal['name']:old('name')}}" >
                    @if($errors->has('calibration_by'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('name') }}</div>
                    @endif
                </div> 
            </div>
         </div>
        <div class="row">    
            
            <div class="col-md-3">
                <div class="form-group ">
                    <label for="contact_email">Contact Email <span class="text-danger">*</span></label>
                    <input  type="text" class="form-control" id="contact_email" name="contact_email"  placeholder=" Contact Email " value="{{(isset($LicenceRenewal['contact_email']) && $LicenceRenewal['contact_email']) ? $LicenceRenewal['contact_email']:old('contact_email')}}" >
                    @if($errors->has('contact_email'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('contact_email') }}</div>
                    @endif
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
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('Modules/Master/Resources/assets/js/others/licence-renewal.js')}}"></script>  
   
@stop