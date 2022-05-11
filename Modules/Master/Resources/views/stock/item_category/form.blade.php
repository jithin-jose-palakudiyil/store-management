<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">
            @if(\Route::getCurrentRoute()->getActionMethod() == 'create')
                Add New 
            @endif
            Item Category</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"  placeholder="Enter Name" value="{{(isset($ItemCategory['name']) && $ItemCategory['name']) ? $ItemCategory['name']:old('name')}}" >
                    @if($errors->has('name'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('name') }}</div>
                    @endif
                </div> 
            </div>
           
            <div class="col-md-4">
             
                
                
                <div class="form-group">
                    <label>Measurement Unit <span class="text-danger">*</span></label>
                    <select class="multiselect-select-all-filtering" multiple="multiple" name="measurement_id[]" id="measurement_id">
                    <?php 
                        $measurements = Modules\Master\Entities\Measurements::where('status',1)->get();
                        
                        if(isset($measurements) && $measurements->isNotEmpty()):
                            foreach ($measurements as $key => $value)   :
                            ?>
                        <option value="{{$value->id}}" >{{$value->name}}</option>
                            <?php
                            endforeach;    
                        endif;
                    ?>
                    
                    </select>
                    <div id="measurement_err">
                        @if($errors->has('measurement_id'))
                            <div class="validation-error-label">{{ $errors->first('measurement_id') }}</div>
                        @endif
                    </div>
                </div>
                
                
                
                
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Status: <span class="text-danger">*</span></label>
                    <select name="status" id="status" data-placeholder="status" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="1"  @if(isset($ItemCategory['status']) && $ItemCategory['status']==1) selected @endif >Active</option>
                        <option value="2" @if(isset($ItemCategory['status']) && $ItemCategory['status']==2) selected @endif>Inactive</option> 
                     </select>
                    <div id="status_err">
                        @if($errors->has('status'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                </div>
            </div> 
            <div class="col-md-3 ">
                <div class="form-group"> 
                     <label class="checkbox-inline">
                         <?php 
                            $checked = null;
                            if($ItemCategory->allow_usage ==1):
                                $checked = 'checked=""';
                            endif;
                         ?>
                         <input type="checkbox" {{$checked}}  class="control-primary" name="allow_usage" value="1">
                        Allow Usage
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
<?php

 if(isset($ItemCategory->belongsToManyMeasurements) && $ItemCategory->belongsToManyMeasurements->isNotEmpty()):
    $measurement_ids =  $ItemCategory->belongsToManyMeasurements->pluck('id')->unique()->toArray();
 ?>
    <script>
            $(function() { 
                $('#measurement_id').multiselect
                ({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    templates: {
                        filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'
                    } 
                });
                $('#measurement_id').multiselect('select',<?php echo json_encode($measurement_ids); ?>);
                $('#measurement_id').multiselect("refresh"); 
            });
            </script> 
    <?php
 endif;
?>
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script> 
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <!--<script src="{{asset('Modules/BackEnd/Resources/assets/validation/jquery.validate.file.js')}}" type="text/javascript"></script>-->
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('Modules/Master/Resources/assets/js/stock/item_category.js')}}"></script> 
@stop