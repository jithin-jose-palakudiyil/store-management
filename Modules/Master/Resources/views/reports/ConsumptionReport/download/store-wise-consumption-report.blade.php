<table border = 1>
    <thead> 
        <tr>
            <th>Item ID</th> 
            <th>Item Name</th>	
            <th>Usage Date</th>
            <th>Usage Quantity</th>
            <th>Unique ID</th>
        </tr>
    </thead> 
    <tbody>
        <?php
      
        foreach ($data as $key => $value):
            ?> 
        <tr>
        
            <td>{{isset($value->item_id) ? $value->item_id : ''}}</td>
            <td>{{isset($value->item_name) ? $value->item_name : ''}}</td>
            <td>{{isset($value->usage_date) ? $value->usage_date : ''}}</td>
            <td>{{isset($value->usage_quantity) ? $value->usage_quantity : ''}}</td>
            <td>
                <?php
                if(isset($value->batch_item_id) && $value->batch_item_id != null):
                    $BatchItems = \Modules\Master\Entities\BatchItems::where('id',$value->batch_item_id)->first();
                    if($BatchItems):
                        echo $BatchItems->unique_id;
                    endif;
                endif;
                ?>
            </td> 
        </tr>  
        <?php endforeach; ?>
    </tbody>
</table>