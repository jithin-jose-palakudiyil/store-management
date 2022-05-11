 
 <div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Breakage</h5>
    </div>
    <div class="panel-body">
        <?php  
        $false = FALSE;
        $breakdown_1 = \Modules\Master\Entities\Breakage:: 
                        select(  'breakage.*' ) 
                        ->join('batch_items','batch_items.id','=', 'breakage.batch_item_id') 
//                        ->join('items','items.id','=', 'batch_items.item_id') 
                        ->where('batch_items.unique_id',$record->unique_id)
                        ->where('breakage.what_is','breakage')
                        ->whereNull('breakage.store_id')
                        ->orderBy('breakage.breakage_date', 'DESC')
                        ->get();  

        if(count($breakdown_1) > 0): ?> 
         
            <?php foreach ($breakdown_1 as $key => $value) :  ?>
              <div class="row"> 
                  <div class="col-md-3">
                       <div class="form-group">
                        <label>Date: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->breakage_date)) : echo $value->breakage_date; endif; ?>" >    
                    </div>
                  </div>
                  <div class="col-md-3">
                       <div class="form-group">
                        <label>Is responsible: </label>
                        <?php 
                        $is_responsible = '';
                        if(isset($value->is_responsible)) :
                           if($value->is_responsible==0):
                               $is_responsible = 'Student';
                           elseif($value->is_responsible==1):
                               $is_responsible = 'Incharge';
                           endif; 
                        endif;
                        ?>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($is_responsible)) : echo $is_responsible; endif; ?>" >    
                    </div>
                  </div>
                  <div class="col-md-3">
                       <div class="form-group">
                        <label>Price: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value->price)) : echo $value->price; endif; ?>" >    
                    </div>
                  </div>
                  <div class="col-md-3">
                       <div class="form-group">
                        <label>Authority  Action : </label>
                         <?php 
                        $AuthorityStatus= '';
                        if(isset($value->status)) :
                           if($value->status==1):
                               $AuthorityStatus = 'collect payment ';
                           elseif($value->status==2):
                               $AuthorityStatus = 'replace item';
                           elseif($value->status==3):
                               $AuthorityStatus = 'maintenance item';
                           endif; 
                        endif;
                        ?>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($AuthorityStatus)) : echo $AuthorityStatus; endif; ?>" >    
                    </div>
                  </div>
                  <div class="col-md-3">
                       <div class="form-group">
                        <label>Status : </label>
                        <?php 
                        $is_status= '';
                        if(isset($value->is_status)) :
                             
                           if($value->is_status==0):
                               $is_status = 'Open';
                           elseif($value->is_status==1):
                               $is_status = 'Open';
                           elseif($value->is_status==2):
                               $is_status = 'Closed'; 
                           endif; 
                        endif;
                        ?>
                        
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($is_status)) : echo $is_status; endif; ?>" >    
                    </div>
                  </div>
              </div>
            <hr/>
            <?php endforeach; ?> 
       
        <?php $false = FALSE; else: $false = true; endif; ?>
            
            
        <?php 
        $breakdown_2 = \Modules\Master\Entities\Breakage:: 
                        select(  'breakage.*' ) 
                        ->join('pivot_store_items','pivot_store_items.id','=', 'breakage.pivot_store_item_id') 
                        ->join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id') 
                        ->where('batch_items.unique_id',$record->unique_id)
                        ->where('breakage.what_is','breakage')
                        ->whereNotNull('breakage.store_id')
                        ->whereNotNull('breakage.pivot_store_item_id')
                        ->orderBy('breakage.breakage_date', 'DESC')
                        ->get();  
         if(count($breakdown_2) > 0): ?> 
         
            <?php foreach ($breakdown_2 as $key => $value_2) :  ?>
              <div class="row"> 
                  <div class="col-md-3">
                       <div class="form-group">
                        <label>Date: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value_2->breakage_date)) : echo $value_2->breakage_date; endif; ?>" >    
                    </div>
                  </div>
                  <div class="col-md-3">
                       <div class="form-group">
                        <label>Is responsible: </label>
                        <?php 
                        $is_responsible_2 = '';
                        if(isset($value_2->is_responsible)) :
                           if($value_2->is_responsible==0):
                               $is_responsible_2 = 'Student';
                           elseif($value_2->is_responsible==1):
                               $is_responsible_2 = 'Incharge';
                           endif; 
                        endif;
                        ?>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($is_responsible_2)) : echo $is_responsible_2; endif; ?>" >    
                    </div>
                  </div>
                  <div class="col-md-3">
                       <div class="form-group">
                        <label>Price: </label>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($value_2->price)) : echo $value_2->price; endif; ?>" >    
                    </div>
                  </div>
                  <div class="col-md-3">
                       <div class="form-group">
                        <label>Authority  Action : </label>
                         <?php 
                        $AuthorityStatus_2= '';
                        if(isset($value_2->status)) :
                           if($value_2->status==1):
                               $AuthorityStatus_2 = 'collect payment ';
                           elseif($value_2->status==2):
                               $AuthorityStatus_2 = 'replace item';
                           elseif($value_2->status==3):
                               $AuthorityStatus_2 = 'maintenance item';
                           endif; 
                        endif;
                        ?>
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($AuthorityStatus_2)) : echo $AuthorityStatus_2; endif; ?>" >    
                    </div>
                  </div>
                  <div class="col-md-3">
                       <div class="form-group">
                        <label>Status : </label>
                        <?php 
                        $is_status_2= '';
                        if(isset($value_2->is_status)) :
                             
                           if($value_2->is_status==0):
                               $is_status_2 = 'Open';
                           elseif($value_2->is_status==1):
                               $is_status_2 = 'Open';
                           elseif($value_2->is_status==2):
                               $is_status_2 = 'Closed'; 
                           endif; 
                        endif;
                        ?>
                        
                        <input  type="text" class="form-control" disabled="" value ="<?php if(isset($is_status_2)) : echo $is_status_2; endif; ?>" >    
                    </div>
                  </div>
              </div>
            <hr/>
            <?php endforeach; ?> 
       
        <?php $false = FALSE; else: $false = true; endif; ?>
            
    
       <?php if(!count($breakdown_1) > 0 && !count($breakdown_2) > 0): ?>
            <code>Sorry, No Breakage Found !</code>
        <?php endif; ?>
            
    </div>
 </div>
 