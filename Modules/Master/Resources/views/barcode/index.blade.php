@extends('master::layouts.master') 
@section('content') 
 <?php if(isset($batch_items) && count($batch_items) > 0): ?>
<input type="button" class="btn btn-danger pull-right" onclick="printDiv('printableArea')" value="print barcode!" />
    <br/> <br/> <br/>
    <div id="printableArea">
        <div style="display:flex; align-items: center; flex-direction: row; flex-wrap: wrap; justify-content: flex-start;">
            <?php foreach ($batch_items as $key => $value) :?> 
                <div style="height:70px;margin-right:20px"> 
                    <?php echo DNS1D::getBarcodeSVG($value->unique_id, 'C39',1,30);  ?>
                    <div style="    text-align: center;"><?php echo ($value->item_name); ?></div>
                     
                </div>    
            <?php  endforeach; ?>  
        </div> 
    </div>
    <?php endif; ?>
    
    
@stop

@section('js')
<script>
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML; 
     document.body.innerHTML = printContents; 
     window.print(); 
     document.body.innerHTML = originalContents;
}
</script>
@stop