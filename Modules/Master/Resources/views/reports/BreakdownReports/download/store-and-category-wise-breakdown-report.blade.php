<table border = 1>
    <thead> 
        <tr>
            <th>Unique ID</th> 
            <th>Item Name</th>	 
            <th>Breakage Date</th>
            <th>Breakage Damaged Status</th>
            <th>Breakage Action</th>
            <th>Breakage Status</th>
            <th>Permanently Damaged Status</th>
        </tr>
    </thead> 
    <tbody>
        <?php
      
        foreach ($data as $key => $value):
            $PivotStoreItems = Modules\Master\Entities\PivotStoreItems::select('pivot_store_items.*','batch_items.unique_id')->
                 join('batch_items','batch_items.id','=', 'pivot_store_items.batch_item_id')->withTrashed()->
                where('pivot_store_items.id',$value->pivot_store_item_id)->first();
         
            ?> 
        <tr>
            <td>{{isset($PivotStoreItems->unique_id) ? $PivotStoreItems->unique_id : ''}}</td>
            <td>{{isset($value->hasOneItem->name) ? $value->hasOneItem->name : ''}}</td>
            <td>{{isset($value->breakage_date) ? $value->breakage_date : ''}}</td>
            <td>
                <?php
                    if($value->step ==2):
                        echo 'close';
                    elseif($value->step ==4):
                         echo 'permanently damaged';
                    endif; 
                ?>
            </td>
            <td>
                 <?php
                    if($value->status ==1):
                        echo 'Collect payment';
                    elseif($value->status ==2):
                         echo 'Replace Item';
                    elseif($value->status ==3):
                         echo 'Maintenance Item';
                    endif; 
                ?>
            </td>
            <td>
                <?php
                
                    if($value->is_status ==0):
                        echo 'open';
                    elseif($value->is_status ==1):
                         echo 'closed';
                    endif; 
                ?>
            </td>
            <td>
                <?php 
                    if($value->is_permanently ==1):
                         echo 'approved ';
                    elseif($value->is_permanently ==2):
                         echo 'rejected ';
                    endif; 
                ?>
            </td>
        </tr>  
        <?php endforeach; ?>
    </tbody>
</table>