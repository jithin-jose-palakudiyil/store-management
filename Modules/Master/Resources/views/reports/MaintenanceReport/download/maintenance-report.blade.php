 <table border = 1>
    <thead> 
        <tr>
            <th>Item ID</th> 
            <th>Item Name</th>	 
            <th>Unique ID</th> 
            <th>Maintenance Type</th> 
            <th>Due Date</th> 
            <th>Completion Date</th> 
            <th>Company Name</th> 
            <th>Contact Number</th> 
            <th>Contact Email</th> 
        </tr>
    </thead> 
    <tbody>
        <?php
         
        foreach ($data as $key => $value):
           
             $maintenance = Modules\Master\Entities\Maintenance::select('maintenance_type.name as maintenance_name','maintenance.*','batch_items.unique_id','items.name as item_name','items.id as item_id')
                ->where('maintenance.id',$value->maintenance_id)
                ->join('batch_items','batch_items.id','=', 'maintenance.batch_item_id')
                ->join('maintenance_type','maintenance_type.id','=', 'maintenance.maintenance_type_id')
                ->join('items','items.id','=', 'batch_items.item_id')
                ->first();
//              dd($maintenance);
            ?> 
        <tr>
            <td>{{isset($maintenance->item_id) ? $maintenance->item_id : ''}}</td>
            <td>{{isset($maintenance->item_name) ? $maintenance->item_name : ''}}</td>
            <td>{{isset($maintenance->unique_id) ? $maintenance->unique_id : ''}}</td>
            <td>{{isset($maintenance->maintenance_name) ? $maintenance->maintenance_name : ''}}</td>
            <td>{{isset($value->date) ? $value->date : ''}}</td>
            <td>{{isset($value->completion_date) ? $value->completion_date : ''}}</td>
            <td>{{isset($maintenance->company_name) ? $maintenance->company_name : ''}}</td>
            <td>{{isset($maintenance->contact_number) ? $maintenance->contact_number : ''}}</td>
            <td>{{isset($maintenance->contact_email) ? $maintenance->contact_email : ''}}</td>
              
        </tr>  
        <?php endforeach; ?>
    </tbody>
</table>