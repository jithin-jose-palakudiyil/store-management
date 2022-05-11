@extends('master::layouts.master')

@section('content')<div class="panel panel-flat">
    <div class="panel-body">
       
        <form method="POST" action="{{route('breakage_reports_download',$slug)}}"   enctype="multipart/form-data">
            <div class="row">
                {{ csrf_field() }} 
                <div class="col-md-6">
                   <div class="form-group">
                       <label>Item Category: <span class="text-danger">*</span></label>
                       <select name="category" id="category" data-placeholder="Select Item Category" class="select " data-minimum-results-for-search="-1">
                           <option></option> 
                           <?php
                           $ItemCategory = \Modules\Master\Entities\ItemCategory::where('status',1)->get();
                           foreach ($ItemCategory as $key => $value) :
                               ?>
                           <option value="{{$value->id}}">{{$value->name}}</option>     
                               <?php
                           endforeach;
                           ?>
                       </select>
                       <div>
                           @if($errors->has('category'))
                               <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('category') }}</div>
                           @endif
                       </div>
                   </div>
                </div>   
                <div class="col-md-2 " style="padding-top: 25px">
                    <button type="submit" class="btn btn-primary"  >Download</button> 
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>
    <script>
        $(function() 
        {
              // Simple select without search
        $('.select').select2({ minimumResultsForSearch: Infinity});

        // Styled checkboxes and radios
        $('.styled').uniform();

        });
    </script>
@stop