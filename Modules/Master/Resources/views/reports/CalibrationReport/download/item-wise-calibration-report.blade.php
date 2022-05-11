 <table border = 1>
    <thead> 
        <tr>
            <th>Item ID</th> 
            <th>Item Name</th>	 
            <th>Unique ID</th> 
            <th>Calibration Type</th> 
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
           
             $calibration = Modules\Master\Entities\Calibration::select('calibration_type.name as calibration_name','calibration.*','batch_items.unique_id','items.name as item_name','items.id as item_id')
                ->where('calibration.id',$value->calibration_id)
                ->join('batch_items','batch_items.id','=', 'calibration.batch_item_id')
                ->join('calibration_type','calibration_type.id','=', 'calibration.calibration_type_id')
                ->join('items','items.id','=', 'batch_items.item_id')
                ->first();
//              dd($maintenance);
            ?> 
        <tr>
            <td>{{isset($calibration->item_id) ? $calibration->item_id : ''}}</td>
            <td>{{isset($calibration->item_name) ? $calibration->item_name : ''}}</td>
            <td>{{isset($calibration->unique_id) ? $calibration->unique_id : ''}}</td>
            <td>{{isset($calibration->calibration_name) ? $calibration->calibration_name : ''}}</td>
            <td>{{isset($value->date) ? date('d-m-Y', strtotime($value->date))  : ''}}</td>
            <td>{{isset($value->completion_date) ? date('d-m-Y', strtotime($value->completion_date)): ''}}</td>
            <td>{{isset($calibration->company_name) ? $calibration->company_name : ''}}</td>
            <td>{{isset($calibration->contact_number) ? $calibration->contact_number : ''}}</td>
            <td>{{isset($calibration->contact_email) ? $calibration->contact_email : ''}}</td>
              
        </tr>  
        <?php endforeach; ?>
    </tbody>
</table>