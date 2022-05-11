 
 <div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Licence Renewal</h5>
    </div>
    <div class="panel-body">
      
            
        <?php 
        $licence_renewal = \Modules\Master\Entities\LicenceRenewal::  
                        join('batch_items','batch_items.id','=', 'licence_renewal.batch_item_id') 
                        ->where('batch_items.unique_id',$record->unique_id) 
                        ->orderBy('licence_renewal.expiry_date', 'DESC')
                        ->get();  
         if(count($licence_renewal) > 0): ?> 
         
            <?php foreach ($licence_renewal as $key => $value) :  ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Licence No: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->licence_no)) : echo $value->licence_no; endif; ?>" >    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Expiry Date: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->expiry_date)) : echo $value->expiry_date; endif; ?>" >    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Name: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->name)) : echo $value->name; endif; ?>" >    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Number: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->contact_number)) : echo $value->contact_number; endif; ?>" >    
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Email: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->contact_email)) : echo $value->contact_email; endif; ?>" >    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Renewed Date: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->renewed_date)) : echo $value->renewed_date; endif; ?>" >    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status: </label>
                        <?php 
                            $status = '';
                            if( isset($value->status) ): 
                                if($value->status ==1):
                                    $status = 'Completed';
                                elseif($value->status ==2):
                                    $status = 'Hold';
                                elseif($value->status ==3):
                                    $status = 'Rejected';
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
            <code>Sorry, No Breakdown Found !</code>
        <?php endif; ?>
            
    </div>
 </div>
 