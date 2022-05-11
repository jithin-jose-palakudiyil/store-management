<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">
            @if(\Route::getCurrentRoute()->getActionMethod() == 'create')
                Add New 
            @endif
            Maintenance</h5>
    </div>
    
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="name">Item Name <span class="text-danger">*</span></label> 
                    <input <?php echo (isset($edit) && $edit) ? 'disabled=""':''; ?> type="text" class="form-control autocomplete" id="item_name" name="item_name"  placeholder="Enter Item Name" value="{{(isset($Maintenance->hasOneBatchItem->hasOneItem->name) && $Maintenance->hasOneBatchItem->hasOneItem->name) ? $Maintenance->hasOneBatchItem->hasOneItem->name:old('item_name')}}" >
                    @if($errors->has('item_name'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('item_name') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="item_id">Unique Id<span class="text-danger">*</span></label>
                    <input <?php echo (isset($edit) && $edit) ? 'disabled=""':''; ?> type="text" readonly="" class="form-control" id="unique_id" name="unique_id"  placeholder="Item Id" value="{{(isset($Maintenance->hasOneBatchItem->unique_id) && $Maintenance->hasOneBatchItem->unique_id) ? $Maintenance->hasOneBatchItem->unique_id:''}}" >
                    <input   type="hidden"   class="form-control" id="item_id" name="item_id"  placeholder="Item Id" value="{{(isset($Maintenance->hasOneBatchItem->id) && $Maintenance->hasOneBatchItem->id) ? $Maintenance->hasOneBatchItem->id:''}}" >
                    @if($errors->has('item_id'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('item_id') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="company_name">Company Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control autocomplete" id="company_name" name="company_name"  placeholder="Company Name" value="{{(isset($Maintenance['company_name']) && $Maintenance['company_name']) ? $Maintenance['company_name']:old('company_name')}}" >
                    @if($errors->has('company_name'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('company_name') }}</div>
                    @endif
                </div> 
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group ">
                    <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number"  placeholder=" Contact Number " value="{{(isset($Maintenance['contact_number']) && $Maintenance['contact_number']) ? $Maintenance['contact_number']:old('contact_number')}}" >
                    @if($errors->has('contact_number'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('contact_number') }}</div>
                    @endif
                </div> 
            </div> 
            <div class="col-md-3">
                <div class="form-group ">
                    <label for="contact_email">Contact Email <span class="text-danger">*</span></label>
                    <input  type="text" class="form-control" id="contact_email" name="contact_email"  placeholder=" Contact Email " value="{{(isset($Maintenance['contact_email']) && $Maintenance['contact_email']) ? $Maintenance['contact_email']:old('contact_email')}}" >
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
                        <option value="1"  @if(isset($Maintenance['status']) && $Maintenance['status']==1) selected @endif >Active</option>
                        <option value="2" @if(isset($Maintenance['status']) && $Maintenance['status']==2) selected @endif>Inactive</option> 
                     </select>
                    <div id="status_err">
                        @if($errors->has('status'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                </div>
            </div> 
            <div class="col-md-3">
                <div class="form-group">
                    <label>Maintenance Type: <span class="text-danger">*</span></label>
                    <select  <?php echo (isset($edit) && $edit) ? 'disabled=""':''; ?> name="maintenance_type_id" id="maintenance_type_id" data-placeholder="Maintenance Type" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <?php 
                        $MaintenanceType = Modules\Master\Entities\MaintenanceType::where('status',1)->get();
                        foreach ($MaintenanceType as $key => $value):
                            $selected =null;
                            if(isset($Maintenance->maintenance_type_id) && $Maintenance->maintenance_type_id==$value->id):
                                $selected ='selected=""';
                            endif;
                            ?> <option {{$selected}} value="{{$value->id}}">{{$value->name}}</option> <?php
                        endforeach;
                        ?>
                    </select>
                    <div id="maintenance_type_id_err">
                        @if($errors->has('maintenance_type_id'))
                            <div   class="validation-error-label" style="display: inline-block;">{{ $errors->first('maintenance_type_id') }}</div>
                        @endif
                    </div>
                </div>
            </div>  
        </div>
       
        @if($errors->has('date')) 
            <div class="alert alert-danger">
                <ul>
                    
                        <li>{{ $errors->first('date') }}</li>
                   
                </ul>
           </div>
                             
        @endif    
        <div class="row date_row"> </div>
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
    <script src="{{asset('Modules/Master/Resources/assets/js/others/maintenance.js')}}"></script> 
    <?php if(isset($Maintenance->maintenance_type_id) && isset($Maintenance->id)): ?>
        <script type="text/javascript"> 
            
    $(function() { maintenance_type({{$Maintenance->maintenance_type_id}},{{$Maintenance->id}});});  </script> 
    <?php endif; ?>
@stop