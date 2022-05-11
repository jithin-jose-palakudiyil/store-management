@extends('master::layouts.master')

@section('content')<div class="panel panel-flat">
    <div class="panel-body">
       
        <form method="POST" action="{{route('stock_reports_download',$slug)}}"   enctype="multipart/form-data">
            <div class="row">
                {{ csrf_field() }} 
                <div class="col-md-5">
                   <div class="form-group">
                        <label>Min Price: <span class="text-danger">*</span></label>
                        <input  type="number"  class="form-control" id="min" name="min"  placeholder="Enter min price" value="" >
                        <div>
                           @if($errors->has('min'))
                               <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('min') }}</div>
                           @endif
                       </div>
                   </div>
                </div>  
                   <div class="col-md-5">
                   <div class="form-group">
                       <label>Max Price: </label>
                       <input  type="number"  class="form-control" id="max" name="max"  placeholder="Enter max price" value="" >
                       <div>
                           @if($errors->has('max'))
                               <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('max') }}</div>
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