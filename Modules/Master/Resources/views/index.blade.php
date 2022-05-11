<!DOCTYPE html>
<html>
<head>
    <title>Test</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <!--<form></form>-->
    <script>
    $(function() 
{ 
        var formData = new FormData(); // Currently empty
        formData.append('username', 'Chris'); 
        formData.append('is_presale', '1'); 
        formData.append('is_launched', '0'); 
      $.ajax
            ({
                type: 'POST',
                url: 'http://localhost/coinxhigh/api/api/b/v1/coins',
//                crossDomain: true, 
//                cache : false,
                dataType    : 'json',
                processData : false,
   
//                dataType: "json",  
//                cache : false,
//                processData: false,
//                crossDomain: true,
//                async: false, contentType: false,
                headers: {
//                    'Origin':'http://127.0.0.1:8000/'
//                    'Accept': '*'
//                    'Access-Control-Allow-Origin': '*', 
//                    'Content-Type':'multipart/form-data; charset=utf-8;boundary=' + Math.random().toString().substr(2)
                },
        
               // async: false,
//                headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
//                data:{'is_presale':1,'is_launched':0},
                data :formData,
                success: function(response)
                { 
                    var obj =  $.parseJSON(JSON.stringify(response));
                    console.log(obj);
                },
                 
        });
    });
    </script>
</body>
</html>