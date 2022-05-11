    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">
                @if(\Route::getCurrentRoute()->getActionMethod() == 'create')
                    Add New 
                @endif
                Gate Pass</h5>
        </div>
    </div>
    <?php 
    $disabled = '';
    $shows = isset($show) ? $show : FALSE;
    if(isset($shows) && $shows):
        $disabled = 'disabled=""';
    endif;
    $itemName = $breakage->name.' - '.$breakage->unique_id; 
    $supplier = \Modules\Master\Entities\BatchItems::select('suppliers.*')
//                            ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')
                            ->join('purchase_entry_batch','purchase_entry_batch.id','=', 'batch_items.batch_id')
                            ->join('purchase_entry','purchase_entry.id','=', 'purchase_entry_batch.purchase_entry_id')
                            ->join('suppliers','suppliers.id','=', 'purchase_entry.supplier_id')
                            ->where('batch_items.id',$breakage->batch_item_id) 
                            ->first();  
   
    ?>
    
    <?php if($shows && \Auth::guard(master_guard)->user()->role!='store'): ?>
    {!! Form::model($GatePass, ['method' => 'PATCH', 'route' => ['gate-pass-m.update',$GatePass->id,'breakage='.$breakage->id],'class'=>'form-valide','id'=>'_formUpdate','enctype'=>'multipart/form-data']) !!}    
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="display-block text-semibold">Breakage Status</label>
                        <?php
                        
                        $checked = ''; $dis = '';
                        if(isset($GatePass->status) && $GatePass->status == 1):
                            $dis = 'disabled=""';
                        endif; ?>
                        <label class="radio-inline">
                            <input {{$dis}} type="radio" name="is_breakage" value="1" class="styled" <?php if(isset($GatePass->is_breakage) && $GatePass->is_breakage == 1): ?> checked="checked" <?php  endif; ?> >
                            Breakage <code>fixed</code> and close the pass    
                        </label>

                        <label class="radio-inline">
                                <input {{$dis}} type="radio" name="is_breakage" value="2" class="styled" <?php if(isset($GatePass->is_breakage) && $GatePass->is_breakage == 2): ?> checked="checked" <?php  endif; ?> >
                                Breakage  <code>not</code> fixed and close the pass   
                        </label>
                        <div id="is_breakage_err">
                        @if($errors->has('is_breakage'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('is_breakage') }}</div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group ">
                        <label for="comments">Comments</label>
                        <textarea {{$dis}}  style="resize: none;height: 100px" type="text" class="form-control" id="comments" name="comments"  placeholder="comments" value="" >{{(isset($GatePass['comments']) && $GatePass['comments']) ? $GatePass['comments']:old('comments')}}</textarea>
                        @if($errors->has('comments'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('comments') }}</div>
                        @endif
                    </div> 
                </div>
            </div>
            <div class="row" <?php if(isset($GatePass->status) && $GatePass->status == 1): ?> style="display: none" <?php  endif; ?> >  
                <div class="col-md-12 ">
                    <button type="submit" class="btn btn-primary pull-right" style="margin-left: 10px">Submit</button> 
                </div>
            </div>
        </div>
    </div>
    <hr/>
    {!! Form::close() !!} 
    <?php  endif; ?>
    
    <div class="panel panel-flat">
        <input type="hidden" class="form-control" name="pivot_store_id" value="{{ isset($breakage->pivot_id) ? $breakage->pivot_id : '' }}" >
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">
                        <label>Item Name </label>
                        <input disabled="" type="text" class="form-control"  value="{{$itemName}}" >
                    </div> 
                </div>
                <div class="col-md-3">
                    <div class="form-group ">
                        <label> Supplier Name</label>
                        <input disabled="" type="text" class="form-control"  value="{{ isset($supplier->name) ? $supplier->name : '' }}" >
                        <input type="hidden" class="form-control" name="supplier_id" value="{{ isset($supplier->id) ? $supplier->id : '' }}" >
                    </div> 
                </div>
                <div class="col-md-3">
                    <div class="form-group ">
                        <label> Supplier Email</label>
                        <input disabled="" type="text" class="form-control"  value="{{ isset($supplier->email) ? $supplier->email : '' }}" >
                    </div> 
                </div>
                <div class="col-md-3">
                    <div class="form-group ">
                        <label> Supplier Phone</label>
                        <input disabled="" type="text" class="form-control"  value="{{ isset($supplier->phone) ? $supplier->phone : '' }}" >
                    </div> 
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">
                        <label for="pass_date">Pass Date <span class="text-danger">*</span></label>
                        <input type="text" {{$disabled}} readonly="" class="form-control datepicker-menus" id="pass_date" name="pass_date"  placeholder="Enter pass date" value="{{(isset($GatePass['pass_date']) && $GatePass['pass_date']) ? $GatePass['pass_date']:old('pass_date')}}" >
                        @if($errors->has('pass_date'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('pass_date') }}</div>
                        @endif
                    </div> 
                </div> 
                <div class="col-md-3">
                    <div class="form-group ">
                        <label for="name">Name<span class="text-danger">*</span></label>
                        <input type="text" {{$disabled}} class="form-control" id="name" name="name"  placeholder="Enter name" value="{{(isset($GatePass['name']) && $GatePass['name']) ? $GatePass['name']:old('name')}}" >
                        @if($errors->has('name'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('name') }}</div>
                        @endif
                    </div> 
                </div> 
                <div class="col-md-3">
                    <div class="form-group ">
                        <label for="contact_number"> Contact Number <span class="text-danger">*</span></label>
                        <input type="text" {{$disabled}}  class="form-control" id="contact_number" name="contact_number"  placeholder="Enter contact number" value="{{(isset($GatePass['contact_number']) && $GatePass['contact_number']) ? $GatePass['contact_number']:old('contact_number')}}" >
                        @if($errors->has('contact_number'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('contact_number') }}</div>
                        @endif
                    </div> 
                </div>
                <div class="col-md-3">
                    <div class="form-group ">
                        <label for="email"> Email <span class="text-danger">*</span></label>
                        <input type="text" {{$disabled}} class="form-control" id="email" name="email"  placeholder="Enter email" value="{{(isset($GatePass['email']) && $GatePass['email']) ? $GatePass['email']:old('email')}}" >
                        @if($errors->has('email'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('email') }}</div>
                        @endif
                    </div> 
                </div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group ">
                        <label for="purpose">Purpose</label>
                        <textarea {{$disabled}}  style="resize: none;height: 100px" type="text" class="form-control" id="purpose" name="purpose"  placeholder="purpose" value="" >{{(isset($GatePass['purpose']) && $GatePass['purpose']) ? $GatePass['comments']:old('purpose')}}</textarea>
                        @if($errors->has('purpose'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('purpose') }}</div>
                        @endif
                    </div> 
                </div>


            </div>

        </div>
    </div>
 
<?php if(!$shows): ?>
    <div class="row">  
        <div class="col-md-12 ">
            <button type="submit" class="btn btn-primary pull-right" style="margin-left: 10px">Submit</button> 
        </div>
    </div>
<?php endif; ?>
@section('js')
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <!--<script src="{{asset('Modules/BackEnd/Resources/assets/validation/jquery.validate.file.js')}}" type="text/javascript"></script>-->
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('Modules/Master/Resources/assets/js/gate-pass/gate-pass.js')}}"></script> 
@stop