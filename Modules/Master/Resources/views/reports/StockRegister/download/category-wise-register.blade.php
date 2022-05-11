 <table border = 1>
    <thead> 
        <tr>
            <th>Item ID</th> 
            <th>Item Name</th>	 
            <th>Quantity</th> 
        </tr>
    </thead> 
    <tbody>
        <?php
         
        foreach ($data as $key => $value):
            $quantity = $value->quantity;
            if(isset($value->hasManyStoreItems) && count($value->hasManyStoreItems) > 0):
                $quantity =$quantity+$value->hasManyStoreItems->sum('quantity'); 
            endif;
            ?> 
        <tr>
            <td>{{$value->id}}</td>
            <td>{{$value->name}}</td>
            <td>{{$quantity}}</td>
        </tr>  
        <?php endforeach; ?>
    </tbody>
</table>