<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">
            @if(\Route::getCurrentRoute()->getActionMethod() == 'create')
                Add New 
            @endif
            Supplier</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"  placeholder="Enter Name" value="{{(isset($Supplier['name']) && $Supplier['name']) ? $Supplier['name']:old('name')}}" >
                    @if($errors->has('name'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('name') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="email" name="email"  placeholder="Email" value="{{(isset($Supplier['email']) && $Supplier['email']) ? $Supplier['email']:old('email')}}" >
                    @if($errors->has('email'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('email') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="phone">Phone <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="phone" name="phone"  placeholder="Phone" value="{{(isset($Supplier['phone']) && $Supplier['phone']) ? $Supplier['phone']:old('phone')}}" >
                    @if($errors->has('phone'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('phone') }}</div>
                    @endif
                </div> 
            </div>
        </div>
        <div class="row">    
            <div class="col-md-4">
                <div class="form-group">
                    <label>Status: <span class="text-danger">*</span></label>
                    <select name="status" id="status" data-placeholder="status" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="1"  @if(isset($Supplier['status']) && $Supplier['status']==1) selected @endif >Active</option>
                        <option value="2" @if(isset($Supplier['status']) && $Supplier['status']==2) selected @endif>Inactive</option> 
                     </select>
                    <div id="status_err">
                        @if($errors->has('status'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="address">Address <span class="text-danger">*</span></label>
                    <textarea style="resize: none;height: 80px" class="form-control" id="address" name="address"  placeholder="Address"  >{{(isset($Supplier['address']) && $Supplier['address']) ? $Supplier['address']:old('address')}}</textarea>
                    @if($errors->has('address'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('address') }}</div>
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
@section('js')
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <!--<script src="{{asset('Modules/BackEnd/Resources/assets/validation/jquery.validate.file.js')}}" type="text/javascript"></script>-->
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('Modules/Master/Resources/assets/js/extras/suppliers.js')}}"></script> 
@stop