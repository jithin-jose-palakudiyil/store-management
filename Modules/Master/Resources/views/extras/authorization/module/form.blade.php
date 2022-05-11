
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                @if(\Route::getCurrentRoute()->getActionMethod() == 'create') Add New  @endif
                Module 
            </h5>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group ">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"  placeholder="Enter Name" value="{{(isset($module['name']) && $module['name']) ? $module['name']:old('name')}}" >
                        @if($errors->has('name'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('name') }}</div>
                        @endif
                    </div> 
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status: <span class="text-danger">*</span></label>
                        <select name="status" id="status" data-placeholder="status" class="select " data-minimum-results-for-search="-1">
                            <option></option> 
                            <option value="1"  @if(isset($module['status']) && $module['status']==1) selected @endif >Active</option>
                            <option value="2" @if(isset($module['status']) && $module['status']==2) selected @endif>Inactive</option> 
                         </select>
                        <div id="status_err">
                            @if($errors->has('status'))
                                <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="form-group"> 
                         <label class="checkbox-inline">
                             <input type="checkbox"  @if(isset($module['is_master']) && $module['is_master']==1) checked="" @endif class="control-primary" name="is_master" value="1" >
                            Is master ?
                        </label>
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="form-group"> 
                         <label class="checkbox-inline">
                             <input type="checkbox" @if(isset($module['is_store']) && $module['is_store']==1) checked="" @endif class="control-primary" name="is_store" value="1">
                            Is store ?
                        </label>
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
    <script src="{{asset('Modules/Master/Resources/assets/js/extras/authorization/module.js')}}"></script> 
@stop