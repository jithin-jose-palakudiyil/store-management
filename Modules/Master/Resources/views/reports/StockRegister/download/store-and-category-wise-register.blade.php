<table border = 1>
    <thead> 
        <tr>
            <th>Item ID</th> 
            <th>Item Name</th>	 
            <th>Quantity</th> 
        </tr>
    </thead> 
    <tbody>
        <?php  foreach ($data as $key => $value): ?> 
        <tr>
            <td>{{$value->item_id}}</td>
            <td>{{$value->item_name}}</td>
            <td>{{$value->quantity}}</td>
        </tr>  
        <?php endforeach; ?>
    </tbody>
</table>