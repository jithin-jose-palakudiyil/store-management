$(function() 
{ 
    $(".action").click(function(){
        
       $("#ActionsDiv").html('');
       var type =  $(this).attr("data-type") ;
       var item_id =  $(this).attr("data-item_id") ;
       var uid =  $(this).attr("data-uid") ;
        GetData(type,item_id,uid);
     
  });
});

function GetData(type,item_id,uid)
{
   
         
        $('.content-wrapper').block
        ({
            message: '<i class="icon-spinner9 spinner"></i>',
            overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
            css: { border: 0, padding: 0, backgroundColor: 'none' }
        });

        var url=base_url+'/'+master_prefix+'/barcode-read-action/'+type+'/'+item_id+'/'+uid;
        $.ajax
            ({
                type: 'GET',
                url: url,
                dataType: "json",
                async: false,
                headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, 
                success: function(response)
                { 
                    var obj =  $.parseJSON(JSON.stringify(response));   
                    $('#ActionsDiv').html(obj.html); 
                   
                },
                error: function (request, textStatus, errorThrown)  {  },
                complete: function() {
                    $('.content-wrapper').unblock();
                },
        });
   }