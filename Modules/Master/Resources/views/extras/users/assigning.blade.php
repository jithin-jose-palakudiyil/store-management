@extends('master::layouts.master')

@section('content')
    
<form method="post" action="{{route('extras.module_permissions_save',$auth->id)}}">
    @csrf
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">  Assigning Permissions</h5> 
            <label class="display-block "> <b>Name: </b>{{ isset($auth->name) ? $auth->name : '' }}</label>
            <label class="display-block "> <b>Username: </b> {{ isset($auth->username) ? $auth->username : '' }}</label>
            
            @if($errors->has('permissions'))
                <div class="validation-error-label">{{ $errors->first('permissions') }}</div>
            @endif
            
        </div> 
        <div class="panel-body">
            <?php
             
            $get_permissions = []; 
            if(isset($auth->belongsToManyPermissions)):
                $get_permissions = $auth->belongsToManyPermissions->pluck('id')->toArray();
            endif;
            foreach ($module_permissions as $key => $module):
                $permissions = null;
                $permissions = $module->hasManyPermissions->all(); 
                if(count($permissions) > 0):      
            ?>
                <div class="form-group">
                    <label class="display-block text-semibold">{{$module->name}}</label>
                    <?php foreach ($permissions as $key => $permission):    
                            $checked = null;
                            if(in_array($permission->id, $get_permissions)):
                                 $checked = 'checked=""';
                            endif; 
                    ?>
                         <label class="checkbox-inline">
                             <input type="checkbox" {{$checked}} class="control-primary" name="permissions[]" value="{{$permission->id}}">
                            {{$permission->name}}
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; endforeach; ?>
            <div class="row">  
                <div class="col-md-12 ">
                    <button type="submit" class="btn btn-primary pull-right" style="margin-left: 10px">Submit</button> 
                </div>
            </div>
        </div>
    </div>
</form>
 
@endsection
@section('js')  
 <script src="{{asset('public//global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
  <script src="{{asset('Modules/Master/Resources/assets/js/extras/users.js')}}"></script>    
@stop