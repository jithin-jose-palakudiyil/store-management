@extends('master::layouts.master')  
@section('content')  
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
                    <input type="text" disabled="" class="form-control" id="invoice_id" name="invoice_id"  placeholder="Enter invoice id" value="{{(isset($PurchaseEntry['invoice_id']) && $PurchaseEntry['invoice_id']) ? $PurchaseEntry['invoice_id']:old('invoice_id')}}" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="invoice_date">Invoice Date <span class="text-danger">*</span></label>
                    <input type="text" disabled=""  class="form-control datepicker-menus" id="invoice_date" name="invoice_date"  placeholder="Enter invoice date" value="{{(isset($PurchaseEntry['invoice_date']) && $PurchaseEntry['invoice_date']) ? $PurchaseEntry['invoice_date']:old('invoice_date')}}" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="total_amount">Total Amount<span class="text-danger">*</span></label>
                    <input type="text" disabled="" class="form-control" id="total_amount" name="total_amount"  placeholder="Enter total amount" value="{{(isset($PurchaseEntry['total_amount']) && $PurchaseEntry['total_amount']) ? $PurchaseEntry['total_amount']:old('total_amount')}}" >
                </div> 
            </div>
        </div>
        
        <div class="row"> 
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="date">Entry Date<span class="text-danger">*</span></label>
                    <input type="text" disabled="" class="form-control datepicker-menus" id="entry_date" name="entry_date"  placeholder="Enter entry date" value="{{(isset($PurchaseEntry['entry_date']) && $PurchaseEntry['entry_date']) ? $PurchaseEntry['entry_date']:old('entry_date')}}" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="invoice_file">Upload Invoice </label><br/>
                     <?php if(isset($PurchaseEntry['invoice_file']) && $PurchaseEntry['invoice_file'] !=null):
                        echo '<span class="label label-success">uploded</span>';
                     else:
                        echo '<span class="label label-warning">not uploded</span> ';     
                     endif; ?>
                </div> 
            </div> 
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="invoice_file">Upload Purchase Order </label> <br/>
                     <?php if(isset($PurchaseEntry['purchase_entry_file']) && $PurchaseEntry['purchase_entry_file'] !=null):
                        echo '<span class="label label-success">uploded</span>';
                     else:
                        echo '<span class="label label-warning">not uploded</span> ';     
                     endif; ?>
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
                    <select disabled="" name="supplier_id" id="supplier_id" data-placeholder="Supplier" class="select " data-minimum-results-for-search="-1">
                        <option></option> 
                        <?php
                       
                        $Suppliers = Modules\Master\Entities\Suppliers::where('status',1)->get();
                        foreach ($Suppliers as $key => $value):
                            $selected ='';
                            if($PurchaseEntry->supplier_id==$value->id):
                                $selected = 'selected=""';
                            endif;
                            ?>
                        <option {{$selected}} value="{{$value->id}}">{{$value->name}}</option>   
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
        </div>
        
        <hr/>
         
    </div>
</div>

<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"> Items 
            
        </h5>
    </div>
    <div class="panel-body">
        <div class="table">
            <?php $i=1; foreach ($PurchaseEntry->hasManyBatch as $key => $value):
//                dd($value);
                $itemName = isset($value->hasOneItem->name) ? $value->hasOneItem->name : '';
                $itemId = isset($value->hasOneItem->id) ? $value->hasOneItem->id : '';
                $expiry_date =  isset($value->expiry_date) ? $value->expiry_date : '';
                $quantity = isset($value->quantity) ? $value->quantity : '';
                $measurements =  isset($value->hasOneItem->hasOneMeasurements->short_code) ? $value->hasOneItem->hasOneMeasurements->short_code : '';
                $amount = isset($value->amount) ? $value->amount : '';
                $make_model = isset($value->make_model) ? $value->make_model : '';
                ?>
                
            <div class="table_row" id="table_row_{{$i}}" >
                <div class="row"> 
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label for="item_id">Item <span class="text-danger">*</span></label>
                            <input type="text" disabled="" class="form-control item_id autocomplete"   placeholder="Enter Item" value="{{$itemName}} - {{$itemId}}" >
                        </div> 
                    </div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label for="make_model">Make/Model<span class="text-danger">*</span></label>
                            <input type="text" class="form-control make_model"    placeholder="Enter make/model" disabled="" value="{{$make_model}}" >
                        </div> 
                    </div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label for="expiry_date">Expiry Date <span class="text-danger">*</span></label>
                            <input type="text" disabled=""  class="form-control datepicker-menus expiry_date"   placeholder="Enter expiry date" value="{{$expiry_date}}" >

                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div class="form-group ">
                            <label for="quantity">Quantity<span class="text-danger">*</span></label>
                            <input type="text" class="form-control quantity"    placeholder="Enter quantity" disabled="" value="{{$quantity}}" >
                        </div> 
                    </div>
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label for="amount">Amount<span class="text-danger">*</span></label>
                            <input type="text" class="form-control amount"    placeholder="Enter Amount" disabled="" value="{{$amount}}" >
                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div class="form-group ">
                            <label for="measurement">Measurement<span class="text-danger">*</span></label>
                            <input type="text" disabled="" class="form-control measurement"   placeholder="Measurement" id="measurement_1" value="{{$measurements}}" >

                        </div> 
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Generate Id:</label>
                            <select name="generate_id[1]" disabled="" id="generate_id_1" data-placeholder="Generate Id" class="form-control generate_id " data-minimum-results-for-search="-1">
                                <option value="0"  <?php if($value->generate_id==0): echo ' selected=""'; endif; ?>>No</option> 
                                <option value="1"  <?php if($value->generate_id==1): echo ' selected=""'; endif; ?>  >Yes</option> 
                             </select>
                        </div>
                    </div>  
                    
                    <?php if($value->generate_id==1): ?>
                        <div class="col-md-1" >
                            <div class="unique_item" data-row="{{$i}}">  
                                <button style="margin-top: 25px" type="button" class="btn border-primary text-primary-300 btn-flat btn-icon btn-rounded " ><i class=" icon-eye-plus"></i></button> 
                            </div>
                        </div>  
                    <?php  endif;?>
                </div> 
                <?php if($value->generate_id==1): ?>
                <?php 
                $unique_ids =[];
                $unique_ids = $value->hasManyBatchItems->pluck('unique_id')->toArray();
                if(!empty($unique_ids)):  
                ?>
                <div   id="rows_{{$i}}" style="display: none" >
                    <div class="col-md-12" style="margin-bottom: 15px !important">
                        <?php foreach ($unique_ids as $values) :?>
                             <span class="label label-default">{{$values}}</span>
                        <?php endforeach; ?>
                       
                    </div> 
                </div>
                <?php $i++; endif; endif;?>
            </div>

            <?php endforeach; ?>
        </div>
        
        <hr/>
        
         
         
    </div>
</div>



@stop
 
                                
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