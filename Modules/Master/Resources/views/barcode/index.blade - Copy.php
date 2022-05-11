@extends('master::layouts.master') 
@section('content') 
 <?php if(isset($batch_items) && count($batch_items) > 0): ?>
<input type="button" class="btn btn-danger pull-right" onclick="printDiv('printableArea')" value="print barcode!" />
    <br/> <br/> <br/>
    <!--<table width="100%" cellspacing=0 cellpadding="0"  style="text-align: center;" >--> 
      <div id="printableArea">
        <table  width="100%" border="1s" cellspacing='5' cellpadding='5'>
            <tbody>    
                <?php $i=1; foreach ($batch_items as $key => $value) :?> 
                <?php if($i==1):  ?><tr valign='top' > <?php endif;  ?> 
                    <td>
                        <?php echo DNS1D::getBarcodeSVG($value->unique_id, 'C39',2,40);  ?> 
                    </td>
                 <?php if($i==4): $i=0; ?></tr> <?php endif;  ?> 
                <?php $i++; endforeach; ?> 

            </tbody>
        </table> 
    <div>
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