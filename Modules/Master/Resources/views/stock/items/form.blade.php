<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">
            @if(\Route::getCurrentRoute()->getActionMethod() == 'create')
                Add New 
            @endif
            Item</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            
           <div class="col-md-4">
                <div class="form-group">
                    <label>Category : <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" data-placeholder="Category" class="select" data-minimum-results-for-search="-1">
                        <option></option> 
                        <?php
                            $ItemCategory = \Modules\Master\Entities\ItemCategory::where('status',1)->get();
                            foreach ($ItemCategory as $key => $value):
                                $selected = null;
                                if($value->id == $Item->category_id):
                                    $selected = 'selected=""';
                                endif;
                                ?>
                                <option {{$selected}} value="{{$value->id}}">{{$value->name}}</option> 
                                <?php
                            endforeach;
                        ?>
                    </select>
                    <div id="category_id_err">
                        @if($errors->has('category_id'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('category_id') }}</div>
                        @endif
                    </div>
                </div>
            </div>
           <div class="col-md-4">
                <div class="form-group">
                    <label>Measurement Unit : <span class="text-danger">*</span></label>
                    <select name="measurement_id" id="measurement_id" data-placeholder="Measurement Unit" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                    </select>
                    <div id="measurement_id_err">
                        @if($errors->has('measurement_id'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('measurement_id') }}</div>
                        @endif
                    </div>
                </div>
            </div> 
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"  placeholder="Enter Name" value="{{(isset($Item['name']) && $Item['name']) ? $Item['name']:old('name')}}" >
                    @if($errors->has('name'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('name') }}</div>
                    @endif
                </div> 
            </div>
        </div>
        <div class="row">  
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="location">Location </label>
                    <input type="text" class="form-control" id="location" name="location"  placeholder="Enter location" value="{{(isset($Item['location']) && $Item['location']) ? $Item['location']:old('location')}}" >
                    @if($errors->has('location'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('location') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Status: <span class="text-danger">*</span></label>
                    <select name="status" id="status" data-placeholder="status" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <option value="1"  @if(isset($Item['status']) && $Item['status']==1) selected @endif >Active</option>
                        <option value="2" @if(isset($Item['status']) && $Item['status']==2) selected @endif>Inactive</option> 
                     </select>
                    <div id="status_err">
                        @if($errors->has('status'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                </div>
            </div> 
            <div class="col-md-4">
                <div class="form-group">
                    <?php
                    $disabled ='';
                    if(isset($Item['has_unique_id']) && $Item['has_unique_id'] ==1):
                         $disabled =' disabled=""';
                    endif;
                    ?>
                    <label>Has unique id ? </label>
                    <select name="has_unique_id" {{$disabled}} id="has_unique_id" data-placeholder="has unique id ?" class="select " data-minimum-results-for-search="-1">
                        <option value="0"  @if(isset($Item['has_unique_id']) && $Item['has_unique_id']==0) selected @endif >No</option>
                        <option value="1" @if(isset($Item['has_unique_id']) && $Item['has_unique_id']==1) selected @endif>Yes</option> 
                     </select>
                    <div id="has_unique_id_err">
                        @if($errors->has('has_unique_id'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
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

    <script src="{{asset('public/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script> 
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <!--<script src="{{asset('Modules/BackEnd/Resources/assets/validation/jquery.validate.file.js')}}" type="text/javascript"></script>-->
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('Modules/Master/Resources/assets/js/stock/items.js')}}"></script> 
    <?php
    if(isset($Item->category_id)):
    ?>
   <script>
     category_change({{$Item->category_id}},{{$Item->measurement_id}});
    </script> 
   <?php
    endif;
    ?>
@stop