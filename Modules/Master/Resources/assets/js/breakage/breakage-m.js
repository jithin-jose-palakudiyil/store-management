var url_prefix ='breakage-m';
$(function() 
{  
    if($('.control-primary').length)
    { 
        // Primary
       $(".control-primary").uniform({
           wrapperClass: 'border-primary-600 text-primary-800'
       }); 
    }
    
    
        /* ********************************************************************* */  
        /* ************************ item autocomplete *************************** */  
        /* ********************************************************************* */ 
        if($('.autocomplete').length){
            $(".autocomplete").keyup(function(){
                $("#price").val('');
            });

        $(".autocomplete").autocomplete({
            source: function (request, response) { 
                //get data item number
                
              
              
                 
                    $.ajax({
                        url:base_url+'/'+master_prefix+'/breakage-autocomplete', 
                        dataType: "json",
                        cache: false,
                        data: {
                            term: request.term,
                        },
                        success: function (data) { 
                            if (data.length === 0) { 
                                data = [{ 'label': request.term+"<span class='open_popup'  data-item='"+request.term+"' style='color: red;'> not found</span>  ", "value": request.term, "id": -1 }];
                                response(data);
                            }else{
                                var resp = $.map(data,function(obj){ 
                                   return { label: obj.name+' - '+obj.unique_id, value: obj.id  ,"batch_item_id": obj.batch_item_id,"amount": obj.amount  } ;
                               }); 
                               response(resp);
                            }  
                        }
                    });

               
            },
            minLength: 1,
            select: function (event, ui) {
                var data_item = event.target.getAttribute("data-item");  
                if (ui.item.id === -1) { 
                    $("#unique_id").val('');
                    $("#batch_item_id").val(''); 
                    $("#price").val(''); 
                    return false;
                } else { 
                    $("#unique_id").val(ui.item.label);
                    $("#batch_item_id").val(ui.item.batch_item_id); 
                    $("#price").val(ui.item.amount); 
                    return false;
                }
            },response: function(event, ui) {}
        }).data("ui-autocomplete")._renderItem = function (ul, item) {return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "</a>").appendTo(ul);};
    }   
          
     
 /* ************************************************************************** */  
/* *************************** initialization ******************************* */  
/* ************************************************************************** */ 

    if($('#datatable').length)
    {   
        
         // Setting datatable defaults
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [10, 20, 50, 100, 200, 500],
        columnDefs: [{ 
            orderable: false,
            width: '100px',
//            targets: [ 5 ]
        }],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function() {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });
  
        var url=$('.datatable-basic').attr('data-url');
        var i = 1;
        $('.datatable-basic').DataTable
      ({
        processing: true,
        serverSide: true, 
        ajax: url,
        "columnDefs": [
            { className: "text-center", "targets": [ 4 ] }
          ],
        columns: [ 
                      
                    {
                        data: "name","searchable": false, sortable: false,
                        render: function (data, type, full) {  return  full.name+' - '+full.unique_id; } 
                    }, 
                    {
                        data: "breakage_date","searchable": false, sortable: false,
                        render: function (data, type, full) {  return  full.breakage_date; } 
                    },
                    {
                        data: "step", sortable: true,  
                        render: function (data, type, full) 
                        { 
                            if(full.step=="0")  { return '<span class="label label-warning">waiting for processing authority</span>';  }
                            else if(full.step=="1")  { return '<span class="label label-success">authority processed</span>';  }
                            else if(full.step=="2")  { return '<span class="label label-default">breakage closed</span>';  }
                            else if(full.step=="3")  { return '<span class="label label-warning">breakage rejected</span>';  }
                            else if(full.step=="4" && full.is_permanently=="0")  { return '<span class="label label-warning">permanently damaged, waiting for processing authority </span>';  }
                            else if(full.step=="4" && full.is_permanently=="1")  { return '<span class="label label-success">permanently damaged, authority approved </span>';  }
                            else if(full.step=="4" && full.is_permanently=="2")  { return '<span class="label label-default">permanently damaged, authority rejected </span>';  }
                        } 
                    },  
                    {
                        data: "is_status", sortable: true,  
                        render: function (data, type, full) 
                        { 
                            if(full.is_status=="0")  { return '<span class="label label-warning">open</span>';  }
                            else if(full.is_status=="1")  { return '<span class="label label-success">closed</span>';  } 
                        } 
                    },
                    {
                        data: "null","searchable": false, sortable: false,
                        render: function (data, type, full)
                        {   
                            var  u ='';
                            if( typeof(editBtn) != "undefined" && editBtn)
                            {
                                var edit_url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+full.id+'/edit';
                                u='<a class="text-center" href="'+edit_url+'"><button type="button" class="btn btn-primary btn-icon"><i class="icon-pencil5"></i></button></a> &nbsp;';
                            }
                            
//                            if( typeof(deleteBtn) != "undefined" && deleteBtn){
//                                var delete_button ='onclick="return ConfirmDelete('+full.id+')"';
//                                u+='<button type="button" '+delete_button+' class="btn btn-danger btn-icon"><i class=" icon-trash"></i></button>&nbsp;&nbsp;';
//                            }
                           
                            if( typeof(gatePassBtn) != "undefined" && gatePassBtn && (full.step=="1" || full.step=="2" || full.step=="3") && full.is_status==0){
                                var gate_pass_url=base_url+'/'+master_prefix+'/gate-pass-m'+'/?breakage='+full.id;
                                u+='<a class="text-center" href="'+gate_pass_url+'"><button type="button" class="btn btn-default btn-icon"><i class="icon-ticket"></i></button></a> &nbsp;';
                            
                            }
                            return u;
                        } 
                    }
        ] 
    });
//        
    }
    
/* ************************************************************************** */  
/* **************************** validate form ******************************* */  
/* ************************************************************************** */ 

    if($('#_form').length)
    { 
          
         // Simple select without search
        $('.select').select2({ minimumResultsForSearch: Infinity});

        // Styled checkboxes and radios
        $('.styled').uniform();


        $("#_form").validate({
            ignore: 'input[type=hidden]', // ignore hidden fields
            errorClass: 'validation-error-label',  successClass: 'validation-valid-label',
            highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
            unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
            // Different components require proper error label placement
            errorPlacement: function(error, element) {  
                if (element.attr("name") == "is_responsible" ){  $("#is_responsible_err").html(error); }
                else if (element.attr("name") == "status" ){  $("#status_err").html(error); }
                else if (element.attr("name") == "what_is" ){  $("#what_is_err").html(error); }
                else if (element.attr("name") == "step" ){  $("#step_err").html(error); }
                else if (element.attr("name") == "is_permanently" ){  $("#is_permanently_err").html(error); }
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'what_is':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        'status':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        'step':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        'is_permanently':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        'unique_id':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        'price':{required:true, normalizer: function(value) { return $.trim(value);  },number: true },
                        'status':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        'step':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        'is_responsible':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        'breakage_date':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        
//                        'name[1]':{required:true, normalizer: function(value) { return $.trim(value);  } },
//                        '_id[1]':{required:true, normalizer: function(value) { return $.trim(value);  } },
//                        'contact_number[1]':{required:true, normalizer: function(value) { return $.trim(value);  } },
//                        'batch[1]':{required:true, normalizer: function(value) { return $.trim(value);  } },
//                        'class[1]':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        
                        
            }
        }); 


        $(document.body).on("change","#is_responsible",function()
        {   


            var is_responsible =$(this).val();
            var txt ='';
            
            if(is_responsible==0){
                txt='Student';
                
                $('.table_row').each(function(index)
                {
                 
                    $(this).find(".batch").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
                    $(this).find(".class").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
                 
                });
        
        
                
//                $('input[name="batch[1]"]').rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
//                $('input[name="class[1]"]').rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
                $(".lab").show();
            }
            if(is_responsible==1){
               
                txt='Lab incharge';
                 $('.table_row').each(function(index)
                {
                 
                    $(this).find(".batch").rules("remove", 'required' ); 
                    $(this).find(".class").rules("remove", 'required'   ); 
                 
                });
                
//                $('input[name="batch[1]"]').rules('remove', 'required');
//                $('input[name="class[1]"]').rules('remove', 'required');
                $(".lab").hide();
            }
            
            
            $(".res_inf").html(txt);
            if(is_responsible.length>0){
                $(".Information").show();
            }
            else{
                 $(".Information").hide();
            }
            //Information

        });
        
        if($('.datepicker-menus').length) 
        { 
            $(".datepicker-menus").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy'

            });
        }
        
        
        
        
        
        
        
        
        
        
        /* ********************************************************************* */  
        /* **************************** add item ******************************* */  
        /* ********************************************************************* */ 

    $(document.body).on("click",".add_item",function()
    { 
         
        $(".table_row:last").clone().hide().appendTo('.table').show('slow'); //taken clone of the last 
        $(".table_row:last").find('input[type=text]').val('');
        $(".table_row:last").find('.validation-error-label').remove();
        var is_responsible = $('#is_responsible :selected').val();
         
        $('.table_row').each(function(index)
        {
            index = parseInt(index)+1; 
            $(this).attr('id','table_row_'+index);
            $(this).find(".name").attr('name','name['+(index)+']');
            $(this).find("._id").attr('name','_id['+(index)+']');
            $(this).find(".contact_number").attr('name','contact_number['+(index)+']');
            $(this).find(".batch").attr('name','batch['+(index)+']');
            $(this).find(".class").attr('name','class['+(index)+']');
            
 
            if(index !=1){ $(this).find(".remove_item").show(); } 
            $(this).find(".remove_item").attr('data-row',index); 
                 
            $(this).find(".name").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
            $(this).find("._id").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
            $(this).find(".contact_number").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
            if(is_responsible==0){ 
            $(this).find(".batch").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
            $(this).find(".class").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
            }
        });
        
    });
      
        
        /* ********************************************************************* */  
        /* ************************* remove item ******************************* */  
        /* ********************************************************************* */ 

        $(document.body).on("click",".remove_item",function()
        {
            var RowRemove = $(this).attr('data-row');
            if(RowRemove !=1){
               
                $('#table_row_'+RowRemove).remove();  
                $('.table_row').each(function(index)
                { 
                    index = parseInt(index)+1; 
                    $(this).attr('id','table_row_'+index);
                    $(this).find(".name").attr('name','name['+(index)+']');
                    $(this).find("._id").attr('name','_id['+(index)+']');
                    $(this).find(".contact_number").attr('name','contact_number['+(index)+']');
                    $(this).find(".batch").attr('name','batch['+(index)+']');
                    $(this).find(".class").attr('name','class['+(index)+']');


                    if(index !=1){ $(this).find(".remove_item").show(); } 
                    $(this).find(".remove_item").attr('data-row',index); 
             
                });
            }     
        });    
             
        
        
        
        
        
        
        
        
        
        
    }
      
});


/* ************************************************************************** */  
/* **************************** Delete Entry ******************************** */  
/* ************************************************************************** */ 

 function ConfirmDelete(id)
    {  
        Noty.overrideDefaults({
            theme: 'limitless',
            layout: 'topRight',
            type: 'alert' 
        });
       var notyConfirm =  new Noty({
                layout: 'center',
                text: 'Are you sure you want to delete it?',
                type: 'info',
                 buttons: [
                    Noty.button('Cancel', 'btn btn-light', function () {
                        notyConfirm.close();
                    }),

                    Noty.button('Yes <i class="icon-paperplane ml-2"></i>', 'btn bg-slate-600 ml-1', function () {
                         var url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+id;   
                        $.ajax
                        ({
                            type: 'DELETE',
                            url: url,
                            dataType: "json",
                            async: false,
                            headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data :{'id':id},
                            success: function(response){  window.location.reload(); },
                            error: function (request, textStatus, errorThrown)  {  }
                            });
                            notyConfirm.close();
                        },
                        {id: 'button1', 'data-status': 'ok'}
                    )
                ]
            }).show();
           return false;
 
         
}
  
    
    
  