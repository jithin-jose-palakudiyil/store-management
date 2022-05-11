 
 <div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Maintenance</h5>
    </div>
    <div class="panel-body">
        <?php  
        $maintenance =  \Modules\Master\Entities\PivotMaintenance:: 
                        select( 'items.name as item_name',  'items.id as item_id','maintenance.company_name','maintenance.contact_number','maintenance.contact_email', 'pivot_maintenance.date', 'pivot_maintenance.completion_date', 'pivot_maintenance.status' )
                        ->join('maintenance','maintenance.id','=', 'pivot_maintenance.maintenance_id')
                        ->join('batch_items','batch_items.id','=', 'maintenance.batch_item_id') 
                        ->join('items','items.id','=', 'batch_items.item_id') 
                        ->where('batch_items.unique_id',$record->unique_id)
                        ->orderBy('pivot_maintenance.date', 'DESC')
                        ->get();  
        if(count($maintenance) > 0): ?> 
         
            <?php foreach ($maintenance as $key => $value) : ?>
            <div class="row"> 
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->date)) : echo $value->date; endif; ?>" >    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Company Name: <span class="text-danger">*</span></label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->company_name)) : echo $value->company_name; endif; ?>" >    
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
            <code>Sorry, No Maintenance Found !</code>
        <?php endif; ?>
    
    </div>
 </div>
 