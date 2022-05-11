@extends('master::layouts.master')

@section('content')<div class="panel panel-flat">
    <div class="panel-body">
       
        <form method="POST" action="{{route('stock_reports_download',$slug)}}"   enctype="multipart/form-data">
            <div class="row">
                {{ csrf_field() }} 
                <div class="col-md-6">
                   <div class="form-group">
                       <label>Store: <span class="text-danger">*</span></label>
                       <select name="store" id="store" data-placeholder="select store" class="select " data-minimum-results-for-search="-1">
                           <option></option> 
                           <?php
                           $store = \Modules\Master\Entities\Store::where('status',1)->get();
                           foreach ($store as $key => $value) :
                               ?>
                           <option value="{{$value->id}}">{{$value->name}}</option>     
                               <?php
                           endforeach;
                           ?>
                       </select>
                       <div id="store_err">
                           @if($errors->has('store'))
                               <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('store') }}</div>
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