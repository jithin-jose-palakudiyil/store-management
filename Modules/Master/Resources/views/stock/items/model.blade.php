<div id="modal_item_batch" class="modal fade" tabindex="-1">
     
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <?php  if(count($purchase_entry_batch) > 0):
                    $hasOneItem = $purchase_entry_batch->pluck('hasOneItem')->first();
                    ?>
                    <h5 class="modal-title"><i class="icon-menu7"></i> &nbsp;
                        {{ isset($hasOneItem->name) ? $hasOneItem->name : '' }}
                    </h5>
                <?php endif; ?>     
            </div> 
            <div class="modal-body">
                <?php 
                
                if(count($purchase_entry_batch) > 0): 
                    
//                $hasOnePurchaseEntry = $purchase_entry_batch->pluck('hasOnePurchaseEntry')->first();
                
                ?>
                    <div class="panel-group panel-group-control panel-group-control-right content-group-lg" id="accordion-control-right">
                    <?php $i=1; foreach ($purchase_entry_batch as $key => $value) : 
                    $BatchItems= $value->hasManyBatchItems;
                     $hasOnePurchaseEntry = $value->hasOnePurchaseEntry;
//                    dd($value);
                    ?>
                        
                    
                        <div class="panel panel-white">
                            <div class="panel-heading">
                                <h6 class="panel-title">
                                <a data-toggle="collapse" class="collapsed" aria-expanded="false" data-parent="#accordion-control-right" href="#accordion-control-right-group{{$i}}">Invoice : {{ (isset($hasOnePurchaseEntry->invoice_id) ? $hasOnePurchaseEntry->invoice_id : '') }}</a>
                                </h6>
                            </div>
                                               <style>
                                .tab{width: 100%;}
.tab th,.tab td {
  border: 1px solid black;
  border-collapse: collapse;
}
.tab th,.tab td {
  padding: 10px;
}
</style>
                            <div id="accordion-control-right-group{{$i}}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="tab"  >
                                        <tr>
                                            <td style="width: 30%"><b>Amount</b></td>
                                            <td style="width: 70%">{{ (isset($value->amount) ? $value->amount : '') }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 30%"><b>Quantity</b></td>
                                            <td style="width: 70%">{{ (isset($value->quantity) ? $value->quantity : '') }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 30%"><b>Expiry Date</b></td>
                                            <td style="width: 70%">{{ (isset($value->expiry_date) ? $value->expiry_date : '') }}</td>
                                        </tr>
                                        <?php 
                                        if(isset($value->generate_id) && $value->generate_id==1 && count($value->hasManyBatchItems)>0):
                                            ?>
                                        <tr>
                                            <td style="width: 30%">
                                                <b>Unique ids</b>
                                            </td>
                                            <td style="width: 70%">
                                                <?php foreach ($value->hasManyBatchItems as $values) :  
                                                    ?>
                                                    <span class="label label-default">{{$values->unique_id}}</span>    
                                                    <?php
                                                endforeach; ?>
                                            </td>
                                        </tr>
                                            <?php
                                        endif;
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>

                    <?php $i++; endforeach; ?>
                    </div>
                <?php else: ?>
                    <h6>Sorry, try again later</h6>
                <?php endif; ?>  
            </div>
        </div>
    </div>
</div>