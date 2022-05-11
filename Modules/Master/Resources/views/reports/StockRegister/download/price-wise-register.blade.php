<table border = 1>
    <thead> 
        <tr>
            <th>Invoice ID</th>
            <th>Purchase Entry ID</th>
            <th>Item ID</th> 
            <th>Item Name</th>	 
            <th>Amount</th> 
        </tr>
    </thead> 
    <tbody>
        <?php  foreach ($data as $key => $value): ?> 
        <tr>
            <td>{{$value->invoice_id}}</td>
            <td>{{$value->purchase_entry_id}}</td>
            <td>{{$value->item_id}}</td>
            <td>{{$value->name}}</td>
            <td>{{$value->amount}}</td>
        </tr>  
        <?php endforeach; ?>
    </tbody>
</table>