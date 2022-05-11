<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">
            @if(\Route::getCurrentRoute()->getActionMethod() == 'create')
                Add New 
            @endif
            User</h5>
    </div>
    <div class="panel-body">
        @if($errors->any())
    {!! implode('', $errors->all('<div class="validation-error-label">:message</div>')) !!}
@endif
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"  placeholder="Enter Name" value="{{(isset($auth['name']) && $auth['name']) ? $auth['name']:old('name')}}" >
                    @if($errors->has('name'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('name') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-3">
                <div class="form-group ">
                    <label for="username">Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="username" name="username"  placeholder="Username" value="{{(isset($auth['username']) && $auth['username']) ? $auth['username']:old('username')}}" >
                    @if($errors->has('username'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('username') }}</div>
                    @endif
                </div> 
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label>Status: <span class="text-danger">*</span></label>
                    <select name="status" id="status" data-placeholder="status" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="1"  @if( (isset($auth['status']) && $auth['status']==1) || (old('status')==1 ) ) selected @endif >Active</option>
                        <option value="2" @if(isset($auth['status']) && $auth['status']==2 || (old('status')==2 ) ) selected @endif>Inactive</option> 
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
            <div class="col-md-4">
                <div class="form-group">
                    <label>Role: <span class="text-danger">*</span></label>
                    <select name="role" id="role" data-placeholder="role" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="master"  @if( (isset($auth['role']) && $auth['role']=='master') || (old('role')=='master' ) ) selected @endif >Warehouse</option>
                        <option value="store" @if(isset($auth['role']) && $auth['role']=='store' || (old('role')=='store' ) ) selected @endif>Store</option> 
                     </select>
                    <div id="status_err">
                        @if($errors->has('status'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                </div>
            </div> 
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="password">Password @if(\Route::getCurrentRoute()->getActionMethod() == 'create')<span class="text-danger">*</span>@endif</label>
                    <input type="password" class="form-control" id="password" name="password"  placeholder="Password" value="" >
                    @if($errors->has('password'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('password') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="confirm_password">Confirm Password @if(\Route::getCurrentRoute()->getActionMethod() == 'create')<span class="text-danger">*</span>@endif</label>
                    <input type="text" class="form-control" id="confirm_password" name="confirm_password"  placeholder="Confirm Password" value="" >
                    @if($errors->has('confirm_password'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('confirm_password') }}</div>
                    @endif
                </div> 
            </div>
        </div>
        <div class="row" id="store_div">
            
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
    <script src="{{asset('Modules/Master/Resources/assets/js/extras/users.js')}}"></script> 
    <?php
    if(isset($auth->role) && $auth->role=='store'):
        ?>
        <script type="text/javascript"> 
            $(function() { get_store('<?=$auth->role?>',{{$auth->store_id}});}); 
        </script>    
        <?php
    endif; 
    ?>
@stop