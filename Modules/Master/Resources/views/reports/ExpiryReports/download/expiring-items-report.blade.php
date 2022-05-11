<table border = 1>
    <thead> 
        <tr>
            <th>Invoice ID</th>
            <th>Purchase Entry ID</th>
            <th>Item ID</th> 
            <th>Item Name</th>	 
            <th>Amount</th>
            <th>Expiring Date</th>
        </tr>
    </thead> 
    <tbody>
        <?php   
        foreach ($data as $key => $value):  
//            $dateObject = \Carbon\Carbon::parse($value->expiry_date)->format('d-m-Y'); 
        $dateObject = date("d/m/Y", strtotime($value->expiry_date));
        ?> 
        <tr>
            <td>{{$value->invoice_id}}</td>
            <td>{{$value->purchase_entry_id}}</td>
            <td>{{$value->item_id}}</td>
            <td>{{$value->name}}</td>
            <td>{{$value->amount}}</td>
            <td>{{$dateObject}}</td>
        </tr>  
        <?php endforeach; ?>
    </tbody>
</table>