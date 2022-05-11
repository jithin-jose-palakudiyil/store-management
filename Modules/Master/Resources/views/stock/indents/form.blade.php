<h5 class="panel-title"> Indent @if(\Route::getCurrentRoute()->getActionMethod() == 'create')  : Item Request  @endif </h5> <br/>

<div class="panel panel-flat"> 
    <div class="panel-body">
        <div class="row"> 
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Date <span class="text-danger">*</span></label>
                    <input type="text" disabled="" class="form-control"  value="<?= date("d-m-Y");?>" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <?php if(\Auth::guard(master_guard)->user()->role !='master'):?> 
                        <?php $storeFrom = \Modules\Master\Entities\Store::where('id',\Auth::guard(master_guard)->user()->store_id)->first();?>
                        <label>Request From <span class="text-danger">*</span></label>
                        <input type="text" disabled="" class="form-control"  value="{{isset($storeFrom->name) ? $storeFrom->name : ''}}" >
                    <?php else:  ?>
                        <label>Request From <span class="text-danger">*</span></label>
                        <input type="text" disabled="" class="form-control"  value="WareHouse" >
                    <?php endif; ?>
                    
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Request To: <span class="text-danger">*</span></label>
                    <?php $store = \Modules\Master\Entities\Store::where('id','!=',\Auth::guard(master_guard)->user()->store_id)->where('status',1)->get()->all();?>
                    <select name="request_to" id="request_to" data-placeholder="Choose Store" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <?php if(\Auth::guard(master_guard)->user()->role !='master'):?> <option value="warehouse">Main Store </option> <?php endif; ?>
                        <?php foreach ($store as $key => $value):  ?>
                        <option value="{{$value->id}}">{{$value->name}}</option> 
                        <?php  endforeach; ?>
                      </select> 
                    <div id="request_to_err"></div>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-md-12">
                <div class="form-group ">
                    <label>Comments</label>
                    <textarea  class="form-control"  name="comments" style="resize: none;height: 100px"></textarea>
                </div> 
            </div>
        </div>
    </div> 
</div>
<!--<h5 class="panel-title"> Item Information  </h5> <br/>-->
@if($errors->any())
    {!! implode('', $errors->all('<div class="validation-error-label">:message</div>')) !!}
@endif
<div class="panel panel-flat ItemInformation" style="display: none">
    <div class="panel-heading">
        <h5 class="panel-title"  >   
            <span>Item Information </span> <button  style="margin-top: -5px;"  type="button" class="pull-right btn border-primary  text-primary-300 btn-flat btn-icon btn-rounded add_item">
                <i class="  icon-plus3"></i>
            </button> 
        </h5>    
    </div>
    <hr  style="padding: 0px;margin: 0px"/>
    <div class="panel-body">
        <div class="table">
             <div class="table_row" id="table_row_1">
                 <div class="row" style="border-bottom: 1px solid #ddd;padding-top: 15px">  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Category: <span class="text-danger">*</span></label>
                            <?php $ItemCategory = Modules\Master\Entities\ItemCategory::where('status',1)->get()->all(); ?>
                            <select name="item_category[1]" id="item_category_1" data-item_category="1" data-placeholder="Choose category" class="form-control  item_category" data-minimum-results-for-search="-1">
                                <option value="">select</option>  
                                <?php foreach ($ItemCategory as $key => $value): ?>
                                <option value="{{$value->id}}">{{$value->name}}</option>  
                                <?php endforeach; ?>
                            </select> 
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group ">
                            <label>Item <span class="text-danger">*</span></label>
                            <input type="text" class="form-control item autocomplete" id="item_1" data-item="1" name="item[1]"  >
                            <input type="hidden" class="form-control item_id" id="item_id_1"  name="item_id[1]"  >
                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div class="form-group ">
                            <label>Unit<span class="text-danger">*</span></label>
                            <input type="text" disabled="" class="form-control unit"  id="unit_1" >
                        </div> 
                    </div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label>Available Qty <span class="text-danger">*</span></label>
                            <input type="text" disabled="" class="form-control QtyAvailable" id="QtyAvailable_1"  >
                        </div> 
                    </div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label>Qty Request<span class="text-danger">*</span></label>
                            <input type="text" class="form-control qty_request" id="QtyRequest_1" name="qty_request[1]" >
                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div class="remove_item" data-row="1" style="display: none" >  
                            <button style="margin-top: 25px" type="button" class="btn border-warning text-warning-300 btn-flat btn-icon btn-rounded " ><i class="icon-cross2"></i></button> 
                        </div>
                    </div>

                </div> 
            </div>
        </div>
        
    </div>
</div>




<div class="row">  
            <div class="col-md-12 ">
                <button type="submit" class="btn btn-primary pull-right" style="margin-left: 10px">Submit</button> 
            </div>
        </div>
@section('js')
<style>
/*     input[type="text"]:disabled {
  background: #ef3636;color: #fff;
}*/
     .ui-autocomplete {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: none;
  float: left;
  min-width: 160px;
  padding: 5px 0;
  margin: 2px 0 0;
  list-style: none;
  font-size: 14px;
  text-align: left;
  background-color: #ffffff;
  border: 1px solid #cccccc;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 4px;
  -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  background-clip: padding-box;
}

.ui-autocomplete > li > div {
  display: block;
  padding: 3px 20px;
  clear: both;
  font-weight: normal;
  line-height: 1.42857143;
  color: #333333;
  white-space: nowrap;
}

.ui-state-hover,
.ui-state-active,
.ui-state-focus {
  text-decoration: none;
  color: #262626;
  background-color: #f5f5f5;
  cursor: pointer;
}

.ui-helper-hidden-accessible {
  border: 0;
  clip: rect(0 0 0 0);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute;
  width: 1px;
}

 </style>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <!--<script src="{{asset('Modules/BackEnd/Resources/assets/validation/jquery.validate.file.js')}}" type="text/javascript"></script>-->
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('Modules/Master/Resources/assets/js/stock/indents.js')}}"></script> 
@stop