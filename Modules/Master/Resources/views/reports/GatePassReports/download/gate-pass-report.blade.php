<table border = 1>
    <thead> 
        <tr>
            <th>Unique ID</th> 
            <th>Item ID</th> 
            <th>Item Name</th>	 
            <th>Breakage / Breakdown ID</th>
            <th>Pass Date</th> 
            <th>Name</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Status</th>
        </tr>
    </thead> 
    <tbody>
        <?php
       
        foreach ($data as $key => $value):  
//            $dateObject = \Carbon\Carbon::parse($value->expiry_date)->format('d-m-Y'); 
//            $dateObject = date("d/m/Y", strtotime($value->expiry_date));
        ?> 
        <tr>
            <td>{{$value->unique_id}}</td>
            <td>{{$value->item_id}}</td>
            <td>{{$value->item_name}}</td>
            <td>{{$value->breakage_id}}</td>
            <td>{{$value->pass_date}}</td>
            <td>{{$value->name}}</td>
            <td>{{$value->email}}</td>
            <td>{{$value->contact_number}}</td>
            <td><?php
            if($value->status==0):
                echo 'Open';
            elseif($value->status==1):    
                 echo 'Closed';
            endif;
            ?></td>
        </tr>  
        <?php endforeach; ?>
    </tbody>
</table>