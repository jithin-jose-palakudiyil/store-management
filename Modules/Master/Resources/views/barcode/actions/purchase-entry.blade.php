 
 <div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"> Purchase Entry </h5>
    </div>
    <div class="panel-body">
        <?php 
        
        $PurchaseEntry = \Modules\Master\Entities\PurchaseEntryBatch:: select(
                        'purchase_entry.invoice_id', 'purchase_entry.invoice_date',
                        'purchase_entry.total_amount', 'purchase_entry.entry_date',
                        'purchase_entry.invoice_file', 'purchase_entry.purchase_entry_file',
                        'purchase_entry.supplier_id', 'purchase_entry_batch.item_id',
                        'purchase_entry_batch.make_model', 'purchase_entry_batch.expiry_date',
                        'purchase_entry_batch.amount', 'purchase_entry_batch.generate_id'
                       )
                        ->join('purchase_entry','purchase_entry.id','=', 'purchase_entry_batch.purchase_entry_id') 
                        ->where('purchase_entry_batch.id',$record->batch_id)->first(); 
        
        if($PurchaseEntry):
            
        
        $item = Modules\Master\Entities\Items::with('hasOneMeasurements:id,short_code')->where('id',$record->item_id)->first(); 
        
        $suppliers = Modules\Master\Entities\Suppliers::where('id',$PurchaseEntry->supplier_id)->first();
        
        ?>
        <div class="row"> 
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="invoice_id">Invoice Id</label>
                    <input type="text" disabled="" class="form-control"  value="{{(isset($PurchaseEntry['invoice_id']) && $PurchaseEntry['invoice_id']) ? $PurchaseEntry['invoice_id']:''}}" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="invoice_date">Invoice Date </label>
                    <input type="text" disabled=""  class="form-control"  value="{{(isset($PurchaseEntry['invoice_date']) && $PurchaseEntry['invoice_date']) ? $PurchaseEntry['invoice_date']:''}}" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="total_amount">Total Amount</label>
                    <input type="text" disabled="" class="form-control"   value="{{(isset($PurchaseEntry['total_amount']) && $PurchaseEntry['total_amount']) ? $PurchaseEntry['total_amount']:''}}" >
                </div> 
            </div>
        </div>
        
        <div class="row"> 
            <div class="col-md-4">
                <div class="form-group ">
                    <label for="date">Entry Date</label>
                    <input type="text" disabled="" class="form-control datepicker-menus" id="entry_date" name="entry_date"  placeholder="Enter entry date" value="{{(isset($PurchaseEntry['entry_date']) && $PurchaseEntry['entry_date']) ? $PurchaseEntry['entry_date']:''}}" >
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
        
        <div class="row">
            <div class="col-md-4">
               <div class="form-group"> 
                   <label>Supplier: </label>
                   <input type="text" disabled="" class="form-control"   placeholder="Enter supplier" value="{{(isset($suppliers['name']) && $suppliers['name']) ? $suppliers['name']:''}}" >
               </div>
            </div> 
        </div>
        <hr/>
        
        
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Item </label>
                    <input type="text" disabled="" class="form-control"   placeholder="Item" value="{{(isset($item['name']) && $item['name']) ? $item['name'].' - '.$item['id']:''}}" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Make/Model</label>
                    <input type="text" disabled="" class="form-control"   placeholder="Make/Model" value="{{(isset($PurchaseEntry['make_model']) && $PurchaseEntry['make_model']) ? $PurchaseEntry['make_model']:''}}" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Expiry Date</label>
                    <input type="text" disabled="" class="form-control"   placeholder="Expiry Date" value="{{(isset($PurchaseEntry['expiry_date']) && $PurchaseEntry['expiry_date']) ? $PurchaseEntry['expiry_date']:''}}" >
                </div> 
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Quantity</label>
                    <input type="text" disabled="" class="form-control"   placeholder="Quantity" value="1" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Amount</label>
                    <input type="text" disabled="" class="form-control"   placeholder="Amount" value="{{(isset($PurchaseEntry['amount']) && $PurchaseEntry['amount']) ? $PurchaseEntry['amount']:''}}" >
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Measurement</label>
                    <input type="text" disabled="" class="form-control"   placeholder="Measurement" value=" {{ isset($item->hasOneMeasurements->short_code) ? $item->hasOneMeasurements->short_code :'' }} " >
                </div> 
            </div>
        </div>
        <div class="row">
            <?php $GenerateId =''; if(isset( $PurchaseEntry->generate_id)):
                if( $PurchaseEntry->generate_id ==0):
                    
                    $GenerateId='No';
                elseif( $PurchaseEntry->generate_id ==1):
                    $GenerateId='Yes';
                endif;
            endif; ?>
            <div class="col-md-4">
                <div class="form-group ">
                    <label>Generate Id</label>
                    <input type="text" disabled="" class="form-control"   placeholder="Generate Id" value="{{$GenerateId}} " >
                </div> 
            </div>
        </div>
        <?php else: ?>
            <code>Sorry, No Purchase Entry Found !</code> 
        <?php endif; ?>
        
    </div>
 </div>
 