<table border = 1>
    <thead> 
        <tr>
            <th>Unique ID</th> 
            <th>Item ID</th> 
            <th>Item Name</th>	 
            <th>License NO</th>
            <th>Expiry Date</th>
            <th>Renewed Date</th>
        </tr>
    </thead> 
    <tbody>
        <?php   
        foreach ($data as $key => $value):   
            $expiry_date = date("d/m/Y", strtotime($value->expiry_date));
            $renewed_date = date("d/m/Y", strtotime($value->renewed_date));
        ?> 
        <tr>
            <td>{{$value->unique_id}}</td>
            <td>{{$value->item_id}}</td>
            <td>{{$value->name}}</td>
            <td>{{$value->licence_no}}</td>
            <td>{{$expiry_date}}</td>
            <td>{{$renewed_date}}</td>
        </tr>  
        <?php endforeach; ?>
    </tbody>
</table>