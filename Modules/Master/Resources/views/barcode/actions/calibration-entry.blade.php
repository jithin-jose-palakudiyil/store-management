 
 <div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Calibration</h5>
    </div>
    <div class="panel-body">
        <?php  
        $calibration = \Modules\Master\Entities\PivotCalibration:: 
                        select( 'items.name as item_name',  'items.id as item_id',
                                'calibration.calibration_by','calibration.contact_number','calibration.contact_email', 'pivot_calibration.date', 
                                'pivot_calibration.completion_date', 'pivot_calibration.status' )
                        ->join('calibration','calibration.id','=', 'pivot_calibration.calibration_id')
                        ->join('batch_items','batch_items.id','=', 'calibration.batch_item_id') 
                        ->join('items','items.id','=', 'batch_items.item_id') 
                        ->where('batch_items.unique_id',$record->unique_id)
                        ->orderBy('pivot_calibration.date', 'DESC')
                        ->get();  
        if(count($calibration) > 0): ?> 
         
            <?php foreach ($calibration as $key => $value) : ?>
            <div class="row"> 
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->date)) : echo $value->date; endif; ?>" >    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Calibration By: <span class="text-danger">*</span></label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->calibration_by)) : echo $value->calibration_by; endif; ?>" >    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Contact Number: <span class="text-danger">*</span></label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->contact_number)) : echo $value->contact_number; endif; ?>" >    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Contact Email: <span class="text-danger">*</span></label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->contact_email)) : echo $value->contact_email; endif; ?>" >    
                    </div>
                </div>
             </div>
            <div class="row"> 
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Completion Date: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->completion_date)) : echo $value->completion_date; endif; ?>" >    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status: </label>
                        <?php
                        $status = '';
                        if(isset($value->status)) :
                            if($value->status==0):
                                $status = 'initialized';
                            elseif($value->status==1):
                                $status = 'completed';
                            elseif($value->status==2):
                                $status = 'hold';
                            elseif($value->status==3):
                                $status = 'rejected';
                            endif; 
                        endif;
                        ?>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($status)) : echo $status; endif; ?>" >    
                    </div>
                </div> 
            </div>
            <hr/>
            <?php endforeach; ?> 
        <?php else: ?>
            <code>Sorry, No Calibration Found !</code>
        <?php endif; ?>
    
    </div>
 </div>
 