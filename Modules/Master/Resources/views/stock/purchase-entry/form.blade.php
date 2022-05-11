<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">
            @if(\Route::getCurrentRoute()->getActionMethod() == 'create')
                Add New 
            @endif
            Purchase Entry </h5>
    </div>
    <div class="panel-body">
        <div class="row"> 
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="invoice_id">Invoice Id <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="invoice_id" name="invoice_id"  placeholder="Enter invoice id" value="{{(isset($PurchaseEntry['invoice_id']) && $PurchaseEntry['invoice_id']) ? $PurchaseEntry['invoice_id']:old('invoice_id')}}" >
                    @if($errors->has('invoice_id'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('invoice_id') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="invoice_date">Invoice Date <span class="text-danger">*</span></label>
                    <input type="text" readonly=""  class="form-control datepicker-menus" id="invoice_date" name="invoice_date"  placeholder="Enter invoice date" value="{{(isset($PurchaseEntry['invoice_date']) && $PurchaseEntry['invoice_date']) ? $PurchaseEntry['invoice_date']:old('invoice_date')}}" >
                    @if($errors->has('invoice_date'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('invoice_date') }}</div>
                    @endif
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="total_amount">Total Amount<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="total_amount" name="total_amount"  placeholder="Enter total amount" value="{{(isset($PurchaseEntry['total_amount']) && $PurchaseEntry['total_amount']) ? $PurchaseEntry['total_amount']:old('total_amount')}}" >
                    @if($errors->has('total_amount'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('total_amount') }}</div>
                    @endif
                </div> 
            </div>
        </div>
        
        <div class="row"> 
<!--            <div class="col-md-4">
                <div class="form-group ">
                    <label for="date">Entry Date<span class="text-danger">*</span></label>
                    <input type="text" readonly="" class="form-control datepicker-menus" id="entry_date" name="entry_date"  placeholder="Enter entry date" value="{{(isset($PurchaseEntry['entry_date']) && $PurchaseEntry['entry_date']) ? $PurchaseEntry['entry_date']:old('entry_date')}}" >
                    @if($errors->has('entry_date'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('entry_date') }}</div>
                    @endif
                </div> 
            </div>-->
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="invoice_file">Upload Invoice </label>
                    <input type="file" class="form-control" id="invoice_file" name="invoice_file"  placeholder="Upload invoice " value="{{(isset($PurchaseEntry['invoice_file']) && $PurchaseEntry['invoice_file']) ? $PurchaseEntry['invoice_file']:old('invoice_file')}}" >
                    @if($errors->has('invoice_file'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('invoice_file') }}</div>
                    @endif
                </div> 
            </div> 
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="invoice_file">Upload Purchase Order </label>
                    <input type="file" class="form-control" id="purchase_entry_file" name="purchase_entry_file"  placeholder="Upload purchase entry " value="{{(isset($PurchaseEntry['purchase_entry_file']) && $PurchaseEntry['purchase_entry_file']) ? $PurchaseEntry['purchase_entry_file']:old('purchase_entry_file')}}" >
                    @if($errors->has('purchase_entry_file'))
                        <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('purchase_entry_file') }}</div>
                    @endif
                </div> 
            </div>
        </div>
        <hr/>
        
        <div class="row"> 
            <div class="panel-heading">
                <h6 class="panel-title">Supplier  Details</h6>
            </div>
            
             <div class="col-md-4">
                <div class="form-group">
                    <label>Supplier: <span class="text-danger">*</span></label>
                    <select name="supplier_id" id="supplier_id" data-placeholder="Supplier" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <?php
                        $Suppliers = Modules\Master\Entities\Suppliers::where('status',1)->get();
                        foreach ($Suppliers as $key => $value):
                            ?>
                              <option value="{{$value->id}}">{{$value->name}}</option>   
                            <?php
                        endforeach;
                        ?>
                        <option value="other">Other</option> 
                    </select>
                    <div id="supplier_id_err">
                        @if($errors->has('supplier_id'))
                            <div   class="validation-error-label" style="display: inline-block;">{{ $errors->first('supplier_id') }}</div>
                        @endif
                    </div>
                </div>
            </div> 
            <div id="supplier_div" style="display: none">
               
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="supplier_name">Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplier_name" name="supplier_name"  placeholder="Supplier Name " value="{{old('supplier_name')}}" >
                            @if($errors->has('supplier_name'))
                                <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('supplier_name') }}</div>
                            @endif
                        </div> 
                    </div>  
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="supplier_email">Supplier Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplier_email" name="supplier_email"  placeholder="Supplier Email " value="{{old('supplier_email')}}" >
                            @if($errors->has('supplier_email'))
                                <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('supplier_email') }}</div>
                            @endif
                        </div> 
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group ">
                            <label for="supplier_phone">Supplier Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplier_phone" name="supplier_phone"  placeholder="Supplier Phone " value="{{old('supplier_phone')}}" >
                            @if($errors->has('supplier_phone'))
                                <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('supplier_phone') }}</div>
                            @endif
                        </div> 
                    </div> 
                 
                    <div class="col-md-6">
                        <div class="form-group ">
                            <label for="supplier_address">Supplier Address <span class="text-danger">*</span></label>
                            <textarea style="resize: none;height: 80px" class="form-control" id="supplier_address" name="supplier_address"  placeholder="Supplier Address"  >{{old('supplier_address')}}</textarea>
                            @if($errors->has('supplier_address'))
                                <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('supplier_address') }}</div>
                            @endif
                        </div> 
                    </div>
               
            </div>
            
        </div>
        
        <hr/>
         
    </div>
</div>

<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"> Items 
            <button   type="button" class="pull-right btn border-primary  text-primary-300 btn-flat btn-icon btn-rounded add_item"><i class="  icon-plus3"></i></button> 
        </h5>
            @if($errors->has('items'))
                <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('items') }}</div>
            @endif
    </div>
    <div class="panel-body">
        <div class="table">
            <div class="table_row" id="table_row_1">
            <div class="row"> 
                <div class="col-md-2">
                    <div class="form-group ">
                        <label for="item_id">Item <span class="text-danger">*</span></label>
                        <input type="text" class="form-control item_id autocomplete" data-id="1" name="item_id[1]"  placeholder="Enter Item" value="" >
                        <input type="hidden" class="form-control hdn_item_id" name="hdn_item_id[1]"   value="" >
                        @if($errors->has('item_id'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('item_id') }}</div>
                        @endif
                    </div> 
                </div>
                <div class="col-md-2">
                    <div class="form-group ">
                        <label for="make_model">Make/Model</label>
                        <input type="text"   class="form-control make_model"   name="make_model[1]"  placeholder="Enter make/model" value="" >
                        @if($errors->has('make_model'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('make_model') }}</div>
                        @endif
                    </div> 
                </div>
                <div class="col-md-2">
                    <div class="form-group ">
                        <label for="expiry_date">Expiry Date </label>
                        <input type="text" readonly=""  class="form-control datepicker-menus expiry_date" id="expiry_date_1" name="expiry_date[1]"  placeholder="Enter expiry date" value="" >
                        @if($errors->has('expiry_date'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('expiry_date') }}</div>
                        @endif
                    </div> 
                </div>
                
                <div class="col-md-1">
                    <div class="form-group ">
                        <label for="quantity">Quantity<span class="text-danger">*</span></label>
                        <input type="text" class="form-control quantity"  name="quantity[1]"  placeholder="Enter quantity" value="" >
                        @if($errors->has('quantity'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('quantity') }}</div>
                        @endif
                    </div> 
                </div>
                <div class="col-md-2">
                    <div class="form-group ">
                        <label for="amount">Rate<span class="text-danger">*</span></label>
                        <input type="text" class="form-control amount" name="amount[1]"  placeholder="Enter Amount" value="" >
                        @if($errors->has('amount'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('amount') }}</div>
                        @endif
                    </div> 
                </div>
                <div class="col-md-1">
                    <div class="form-group ">
                        <label for="measurement">Measurement<span class="text-danger">*</span></label>
                        <input type="text" disabled="" class="form-control measurement"   placeholder="Measurement" id="measurement_1" value="" >
                        @if($errors->has('measurement'))
                            <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('measurement') }}</div>
                        @endif
                    </div> 
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Generate Id:</label>
                        <select name="generate_id[1]" disabled="" id="generate_id_1" data-placeholder="Generate Id" class="form-control generate_id " data-minimum-results-for-search="-1">
                            <option value="0"  >No</option> 
                            <option value="1"    >Yes</option> 
                         </select>
                        <div class="generate_id_err">
                            @if($errors->has('generate_id'))
                                <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('generate_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>  
             
                <div class="col-md-1">
                    <div class="remove_item" data-row="1" style="display: none">  
                        <button style="margin-top: 25px" type="button" class="btn border-warning text-warning-300 btn-flat btn-icon btn-rounded " ><i class="icon-cross2"></i></button> 
                    </div>
                </div> 
            </div> 
            </div>
        </div>
        
        <hr/>
        
         
        <div class="row">  
            <div class="col-md-12 ">
                <button type="submit" class="btn btn-primary pull-right" style="margin-left: 10px">Submit</button> 
            </div>
        </div>
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
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script> 
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/jquery.validate.file.js')}}" type="text/javascript"></script>
    <script src="{{asset('Modules/Master/Resources/assets/js/stock/purchase-entry.js')}}"></script> 
  
@stop