@extends('master::layouts.master')

@section('content')
    
<form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{route('extras.roles_save',$permission->id)}}">
    @csrf
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">  Assigning Roles to Permission</h5> 
            <small> {{$permission->name}}</small>
            @if($errors->has('role'))
                <div class="validation-error-label">{{ $errors->first('role') }}</div>
            @endif
        </div>
        
        <div class="panel-body">
            <?php
            foreach ($roles as $key => $role):
                $checked =null; 
                if($role->hasPermissionTo($permission->name)):
                     $checked ='checked=""';
                endif;
            ?> 
            <div class="col-md-3 ">
                <div class="form-group"> 
                         <label class="checkbox-inline">
                             <input type="checkbox" {{$checked}} class="control-primary" name="role[]" value="{{$role->id}}">
                            {{$role->name}}
                        </label>
                </div>
            </div>
            <?php endforeach; ?>
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
  <script src="{{asset('Modules/Master/Resources/assets/js/extras/authorization/permission.js')}}"></script>    
@stop