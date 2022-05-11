@extends('master::layouts.master')

@section('content')<div class="panel panel-flat">
    <div class="panel-body">
       
        <form method="POST" action="{{route('calibration_reports_download',$slug)}}"   enctype="multipart/form-data">
            <div class="row">
                {{ csrf_field() }} 
                <div class="col-md-3">
                   <div class="form-group">
                       <label>Report Type: <span class="text-danger">*</span></label>
                       <select name="report_type" id="store" data-placeholder="Select Report Type" class="select " data-minimum-results-for-search="-1">
                           <option></option> 
                           <option value="completion">Completion Report</option>
                           <option value="due">Due Report</option>
                           
                       </select>
                       <div >
                           @if($errors->has('report_type'))
                               <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('report_type') }}</div>
                           @endif
                       </div>
                   </div>
                </div>   
                <div class="col-md-3">
                   <div class="form-group">
                       <label>From: </label>
                       <input  type="text" readonly=""  class="form-control datepicker-menus" id="from" name="from"  placeholder="Enter from date" value="" >
                        <div>
                           @if($errors->has('from'))
                               <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('from') }}</div>
                           @endif
                       </div>
                   </div>
                </div>  
                 <div class="col-md-3">
                   <div class="form-group">
                       <label>To: </label>
                       <input  type="text" readonly=""  class="form-control datepicker-menus" id="to" name="to"  placeholder="Enter to date" value="" >
                        <div>
                           @if($errors->has('to'))
                               <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('to') }}</div>
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
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

            if($('.datepicker-menus').length) 
            { 
                $(".datepicker-menus").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd'

                });
            }
                        
        });
    </script>
@stop