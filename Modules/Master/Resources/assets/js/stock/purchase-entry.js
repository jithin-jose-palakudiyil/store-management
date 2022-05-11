var url_prefix ='purchase-entry';
$(function() 
{  
   if($('.select').length)
    { 
        $('.select').select2({ minimumResultsForSearch: Infinity});
        $('.styled').uniform(); 
    } 
    
    /* ************************************************************************* */  
    /* ******************** initialization ************************************* */  
    /* ************************************************************************* */ 
    
    if($('.control-primary').length)
    { 
        // Primary
       $(".control-primary").uniform({
           wrapperClass: 'border-primary-600 text-primary-800'
       }); 
    }
    
        $(document.body).on("click",".unique_item",function()
        {    
           
           var row =  $(this).attr("data-row");;
           $(this).hide();
           $('#rows_'+row).show();
           
        });
    //
    /* ************************************************************************* */  
    /* ************************** datatable ************************************ */  
    /* ************************************************************************* */ 

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
            processing: true, serverSide: true,  ajax: url,
            "columnDefs": [ { className: "text-center", "targets": [ 3] } ],
            columns:    [ 
                            {
                            data: "invoice_id", sortable: true,
                            render: function (data, type, full) {  return  full.invoice_id; } 
                            },   
                            {
                                data: "invoice_date", sortable: true,
                                render: function (data, type, full) {  return  full.invoice_date; } 
                            }, 
                            {
                                data: "total_amount", sortable: true,
                                render: function (data, type, full) {  return  full.total_amount; } 
                            }, 
//                            {
//                                data: "invoice_file", sortable: true,
//                                render: function (data, type, full) {  return  full.invoice_file; } 
//                            }, 
//                            {
//                                data: "status", sortable: true,  
//                                render: function (data, type, full) 
//                                { 
//                                    if(full.status=="1")  { return '<span class="label label-success">Active</span>';  }
//                                    else if(full.status=="2")  { return '<span class="label label-warning">Inactive</span>';  }
//                                } 
//                            },  
                            {
                                data: "null","searchable": false, sortable: false,
                                render: function (data, type, full)
                                {   
                                    var  u ='';
                                    if( typeof(viewBtn) != "undefined" && viewBtn)
                                    {
                                        var show_url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+full.id;
                                        u='<a class="text-center" href="'+show_url+'"><button type="button" class="btn btn-warning btn-icon"><i class=" icon-eye4"></i></button></a> &nbsp;';
                                    }
//
//                                    if( typeof(deleteBtn) != "undefined" && deleteBtn){
//                                        var delete_button ='onclick="return ConfirmDelete('+full.id+')"';
//                                        u+='<button type="button" '+delete_button+' class="btn btn-danger btn-icon"><i class=" icon-trash"></i></button>&nbsp;&nbsp;';
//                                    } 
                                    return u;
                                } 
                            }
                        ] 
      });      
    }
    
    /* ************************************************************************* */  
    /* ************************ form ******************************************* */  
    /* ************************************************************************* */ 

    if($('#_form').length)
    {
        /* ********************************************************************* */  
        /* ************************ category change **************************** */  
        /* ********************************************************************* */ 

        $(document.body).on("change","#category_id",function()
        {    
            var category_id =$(this).val(); 
            category_change(category_id); 
        });
        
        $(document.body).on("submit","#_form",function(e)
        {     
            if (confirm("Are you sure, want to submit the purchase entry ?. after submission no edit possible.")) 
            {
                return true;
            }
            e.preventDefault();
            return false;
        });
           
            
            
        /* ********************************************************************* */  
        /* ************************ Item Form submit *************************** */  
        /* ********************************************************************* */ 

        $(document).on("submit","#modelItemForm",function(e) {
             
            e.preventDefault(); 
            var category_id=document.getElementById("category_id").value;  
            var measurement_id=document.getElementById("measurement_id").value;
            var name=document.getElementById("name").value;
            var location=document.getElementById("location").value;
            var has_unique_id=document.getElementById("has_unique_id").value;
            if($("#modelItemForm").valid()){
               $('.content-wrapper').block
                ({
                    message: '<i class="icon-spinner9 spinner"></i>',
                    overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
                    css: { border: 0, padding: 0, backgroundColor: 'none' }
                }); 
                var url_form=base_url+'/'+master_prefix+'/'+url_prefix+'/store-item';
                $.ajax
                ({
                    type: 'POST',
                    url: url_form,
                    dataType: "json",
                    async: false,
                    headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data :{'has_unique_id':has_unique_id,'category_id':category_id,'measurement_id':measurement_id,'name':name,'location':location},
                    success: function(response)
                    { 
                       var obj =  $.parseJSON(JSON.stringify(response)); 
                       if(obj.error!=null){
                           $("#Modelerrors").html(obj.error); 
                       }else{
                        if(obj.error==null && obj.create!=null){
                            var data_name =$("#modelItemForm").attr("data-input");
                            var data_row =$("#modelItemForm").attr("data-row");
                            $("input[name='"+data_name+"']").val(obj.create.name+" - "+obj.create.id);
                            $("input[name='hdn_"+data_name+"']").val(obj.create.id);
                            if(obj.create.has_unique_id==1){
                                 $("#generate_id_"+data_row).attr('disabled','disabled');
//                                $("#generate_id_"+data_row).removeAttr('disabled');
                                 $('#generate_id_'+data_row+' option[value="1"]').attr("selected", "selected");
                            }
                            else{
                                $("#generate_id_"+data_row).attr('disabled','disabled');
                                $('#generate_id_'+data_row+' option[value="0"]').attr("selected", "selected");
                            }
                            $("#measurement_"+data_row).val(obj.create.has_one_measurements.short_code); 
                            $('#modal_theme_primary').modal('hide');
                        }
                       }
                    },
                    error: function (request, textStatus, errorThrown)  {  },
                    complete: function() {
                        $('.content-wrapper').unblock();
                    },
                }); 
            } 
            return false; 
        });
    
        /* ********************************************************************* */  
        /* ************************ item autocomplete *************************** */  
        /* ********************************************************************* */ 

        $(".autocomplete").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url:base_url+'/'+master_prefix+'/purchase-entry-autocomplete', 
                    dataType: "json",
                    cache: false,
                    data: {
                        term: request.term, 
                    },
                    success: function (data) {
                        if (data.length === 0) {
                            data = [{ 'label': "<span class='open_popup'  data-item='"+request.term+"' style='color: red;'>Click here to create</span>  "+request.term, "value": request.term, "id": -1 }];
                            response(data);
                        }else{
                            var resp = $.map(data,function(obj){ 
                               return { label: obj.name+' - '+obj.id, name: obj.name, value: obj.id  ,"id": obj.id ,'has_unique_id':obj.has_unique_id,'short_code': obj.has_one_measurements.short_code } ;
                           }); 
                           response(resp);
                        }  
                    }
                });
            },
            minLength: 1,
            select: function (event, ui) {
                if (ui.item.id === -1) {
                     open_popup(event.target);
                    return false;
                } else {
//                    console.log(ui.item);
                    var data_id = event.target.getAttribute("data-id"); 
                    if(ui.item.has_unique_id==1){ 
                                
                                $("#generate_id_"+data_id).removeAttr('disabled'); 
                                $('#generate_id_'+data_id+' option[value="1"]').removeAttr("disabled");
                                $('#generate_id_'+data_id+' option[value="0"]').removeAttr("disabled");
                                
                                $('#generate_id_'+data_id+' option[value="0"]').removeAttr("selected");
                                $('#generate_id_'+data_id+' option[value="1"]').attr("selected", "selected");
                                 
                                $('#generate_id_'+data_id+' option[value="0"]').attr("disabled", "disabled"); 
                               
                    }
                    else{
                                $('#generate_id_'+data_id+' option[value="0"]').removeAttr("disabled");
                                $('#generate_id_'+data_id+' option[value="1"]').removeAttr("disabled");
                                
                                $('#generate_id_'+data_id+' option[value="1"]').removeAttr("selected");
                                $('#generate_id_'+data_id+' option[value="0"]').attr("selected", "selected");
                                
                                $('#generate_id_'+data_id+' option[value="1"]').attr("disabled", "disabled");
                                $("#generate_id_"+data_id).attr('disabled','disabled');
                    }
                            
                            
                    $("#measurement_"+data_id).val(ui.item.short_code);
                    $("input[name='"+event.target.name+"']").val(ui.item.label);
                    $("input[name='hdn_"+event.target.name+"']").val(ui.item.id);
                    return false;
                }
            },response: function(event, ui) {}
        }).data("ui-autocomplete")._renderItem = function (ul, item) {return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "</a>").appendTo(ul);};
          
        /* ********************************************************************* */  
        /* ************************* supplier change *************************** */  
        /* ********************************************************************* */ 

        $(document.body).on("change","#supplier_id",function()
        {   

            var supplier_id =$(this).val(); 
            if(supplier_id=='other'){
               $('#supplier_div').show();
            }else{
                 $('#supplier_div').hide();
            }

        }); 
        
        /* ********************************************************************* */  
        /* **************************** initialization ************************* */  
        /* ********************************************************************* */ 

        
        if($('.datepicker-menus').length) 
        { 
            $(".datepicker-menus").datepicker({
                changeMonth: true,
                changeYear: true,
                //dateFormat: 'dd-mm-yy'
                dateFormat: 'yy-mm-dd'

            });
        }
        
        /* ********************************************************************* */  
        /* ************************* form validate ***************************** */  
        /* ********************************************************************* */ 

        $("#_form").validate({
            ignore: 'input[type=hidden]', // ignore hidden fields
            errorClass: 'validation-error-label',  successClass: 'validation-valid-label',
            highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
            unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
            // Different components require proper error label placement
            errorPlacement: function(error, element) {  
                if (element.attr("name") == "supplier_id" ){  $("#supplier_id_err").html(error); } 
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'invoice_id':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                        'invoice_date':{required:true, normalizer: function(value) { return $.trim(value);  },   },
                        'total_amount':{required:true, normalizer: function(value) { return $.trim(value);  },number: true  },
                        'entry_date':{required:true, normalizer: function(value) { return $.trim(value);  },  },
                        'invoice_file':  {   fileType: { types: ["jpg", "jpeg", "pdf", "png"] }, maxFileSize: { "unit": "MB",  "size": 2  }, }, 
                        'purchase_entry_file':  { fileType: { types: ["jpg", "jpeg", "pdf", "png"] }, maxFileSize: { "unit": "MB",  "size": 2  }, }, 
                        'supplier_id':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        'supplier_name':{ normalizer: function(value) { return $.trim(value);  },required: function(element){ return $("#supplier_id").val()=="other"; } },
                        'supplier_email':{ normalizer: function(value) { return $.trim(value);  },required: function(element){ return $("#supplier_id").val()=="other"; } },
                        'supplier_phone':{ normalizer: function(value) { return $.trim(value);  },required: function(element){ return $("#supplier_id").val()=="other"; } },
                        'supplier_status':{ normalizer: function(value) { return $.trim(value);  },required: function(element){ return $("#supplier_id").val()=="other"; } },
                        'supplier_address':{ normalizer: function(value) { return $.trim(value);  },required: function(element){ return $("#supplier_id").val()=="other"; },minlength: 10, },
                        
                        
                        
                        'item_id[1]':{required:true, normalizer: function(value) { return $.trim(value);  }  },
//                        'expiry_date[1]':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                        'quantity[1]':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                        'amount[1]':{required:true, normalizer: function(value) { return $.trim(value);  }   },
                        
           }
        });  
        
        /* ********************************************************************* */  
        /* **************************** add item ******************************* */  
        /* ********************************************************************* */ 

        $(document.body).on("click",".add_item",function()
        { 
            $(".table_row:last").clone().hide().appendTo('.table').show('slow'); //taken clone of the last
            $(".table_row:last").find('input[type=text]').val('');
            $(".table_row:last").find('select option:selected').removeAttr("selected");
            $(".table_row:last").find(".expiry_date").removeAttr('class').addClass('form-control datepicker-menus expiry_date');
            $(".table_row:last").find('.validation-error-label').html('');
            $(".table_row:last").find('.generate_id').attr('disabled','disabled');
            $('.table_row').each(function(index)
            {
                index = parseInt(index)+1; 
                $(this).attr('id','table_row_'+index);
                $(this).find(".item_id").attr('name','item_id['+(index)+']');
                $(this).find(".item_id").attr("data-id", index);
                $(this).find(".hdn_item_id").attr('name','hdn_item_id['+(index)+']');
                $(this).find(".measurement").attr("id", 'measurement_'+index); 
                $(this).find(".make_model").attr('name','make_model['+(index)+']');
                $(this).find(".expiry_date").attr('name','expiry_date['+(index)+']');
                $(this).find(".expiry_date").attr('id','expiry_date_'+(index)); 
                $(this).find("#expiry_date_"+(index)).datepicker({
                    changeMonth: true,
                    changeYear: true,
                   // dateFormat: 'dd-mm-yy'
                    dateFormat: 'yy-mm-dd'

                }); 
                $(this).find(".quantity").attr('name','quantity['+(index)+']');
                $(this).find(".generate_id").attr('name','generate_id['+(index)+']');
                $(this).find(".generate_id").attr("id", 'generate_id_'+index);
                $(this).find(".amount").attr('name','amount['+(index)+']');
                if(index !=1){ $(this).find(".remove_item").show(); } 
                $(this).find(".remove_item").attr('data-row',index); 
                
                $(this).find(".item_id").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
//                $(this).find(".expiry_date").rules("add",{ required:true,  normalizer: function(value) { return $.trim(value);  }  }); 
                $(this).find(".quantity").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  },digits: true });
                $(this).find(".amount").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  },number: true  });
                
                
                $(this).find(".autocomplete").autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url:base_url+'/'+master_prefix+'/purchase-entry-autocomplete', 
                            dataType: "json",
                            cache: false,
                            data: { term: request.term, },
                            success: function (data) {
                                if (data.length === 0) {
                                    data = [{ 'label': "<span class='open_popup'  data-item='"+request.term+"' style='color: red;'>Click here to create</span>  "+request.term, "value": request.term, "id": -1 }];
                                    response(data);
                                }else{
                                    var resp = $.map(data,function(obj){ 
                                       return { label: obj.name+' - '+obj.id, name: obj.name,'has_unique_id':obj.has_unique_id, value: obj.id  ,"id": obj.id  ,'short_code': obj.has_one_measurements.short_code } ;
                                   });  
                                   response(resp);
                                }

                            }
                        });
                    },
                    minLength: 1,
                    select: function (event, ui) {
                        if (ui.item.id === -1) {
                            open_popup(event.target);
                            return false;
                        } else {
//                             console.log(ui.item);
                            var data_id = event.target.getAttribute("data-id");
                            if(ui.item.has_unique_id==1){ 
                                
                                $("#generate_id_"+data_id).removeAttr('disabled'); 
                                $('#generate_id_'+data_id+' option[value="1"]').removeAttr("disabled");
                                $('#generate_id_'+data_id+' option[value="0"]').removeAttr("disabled");
                                
                                $('#generate_id_'+data_id+' option[value="0"]').removeAttr("selected");
                                $('#generate_id_'+data_id+' option[value="1"]').attr("selected", "selected");
                                 
                                $('#generate_id_'+data_id+' option[value="0"]').attr("disabled", "disabled"); 
                               
                            }
                            else{
                                $('#generate_id_'+data_id+' option[value="0"]').removeAttr("disabled");
                                $('#generate_id_'+data_id+' option[value="1"]').removeAttr("disabled");
                                
                                $('#generate_id_'+data_id+' option[value="1"]').removeAttr("selected");
                                $('#generate_id_'+data_id+' option[value="0"]').attr("selected", "selected");
                                
                                $('#generate_id_'+data_id+' option[value="1"]').attr("disabled", "disabled");
                                $("#generate_id_"+data_id).attr('disabled','disabled');
                            }
                            $("#measurement_"+data_id).val(ui.item.short_code);
                            $("input[name='"+event.target.name+"']").val(ui.item.label);
                            $("input[name='hdn_"+event.target.name+"']").val(ui.item.id);
                            return false;
                        }
                    }, response: function(event, ui) { }
                }).data("ui-autocomplete")._renderItem = function (ul, item) {return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "</a>").appendTo(ul);};
        
        
        
        
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
                    $(this).find(".item_id").attr('name','item_id['+(index)+']');
                    $(this).find(".item_id").attr("data-id", index);
                    $(this).find(".measurement").attr("id", 'measurement_'+index);
                    $(this).find(".hdn_item_id").attr('name','hdn_item_id['+(index)+']');
                    $(this).find(".make_model").attr('name','make_model['+(index)+']');
                    $(this).find(".expiry_date").attr('name','expiry_date['+(index)+']');
                    $(this).find(".expiry_date").attr('id','expiry_date_'+(index)); 
                    $(this).find(".quantity").attr('name','quantity['+(index)+']');
                    $(this).find(".generate_id").attr('name','generate_id['+(index)+']');
                    $(this).find(".generate_id").attr("id", 'generate_id_'+index);
                    $(this).find(".amount").attr('name','amount['+(index)+']');
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

    /* ************************************************************************** */  
    /* **************************** open item popup ***************************** */  
    /* ************************************************************************** */ 

    function open_popup(event)
    {

                $('.content-wrapper').block
                ({
                    message: '<i class="icon-spinner9 spinner"></i>',
                    overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
                    css: { border: 0, padding: 0, backgroundColor: 'none' }
                });

                var url_form=base_url+'/'+master_prefix+'/'+url_prefix+'/add-new-item-model';
                $.ajax
                ({
                    type: 'Get',
                    url: url_form,
                    dataType: "json",
                    async: false,
                    headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data :{'value':event.value},
                    success: function(response)
                    { 
                        var obj =  $.parseJSON(JSON.stringify(response));
                        if(obj.html){ 
                            $('#modal_item').html(obj.html);
                            // Simple select without search
                            $('.selectM').select2({ minimumResultsForSearch: Infinity});

                            // Styled checkboxes and radios
                            $('.styled').uniform(); 
                            $("#modelItemForm").attr("data-input", event.name);
                            $("#modelItemForm").attr("data-row", event.getAttribute("data-id"));
                            $("#modelItemForm").validate({
                                ignore: 'input[type=hidden]', // ignore hidden fields
                                errorClass: 'validation-error-label',  successClass: 'validation-valid-label',
                                highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
                                unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
                                // Different components require proper error label placement
                                errorPlacement: function(error, element) {  
                                    if (element.attr("name") == "status" ){  $("#status_err").html(error); }
                                    else if (element.attr("name") == "measurement_id" ){  $("#measurement_id_err").html(error); }
                                    else if (element.attr("name") == "category_id" ){  $("#category_id_err").html(error); }
                                    else{  error.insertAfter(element);}       
                                }, 
                                rules: {  
                                    'name':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                                    'category_id':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                                    'measurement_id':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                                }
                            });

                            $('#modal_theme_primary').modal('show');
                        }
                        else{
                            if($('#modal_theme_primary').length){$('#modal_theme_primary').modal('hide');}
                            $('#modal_item').html('');   
                        }


                    },
                    error: function (request, textStatus, errorThrown)  {  },
                    complete: function() {
                        $('.content-wrapper').unblock();
                    },
            });      
    }
    

    /* ************************************************************************** */  
    /* ************************* popup category change ************************** */  
    /* ************************************************************************** */ 


    function  category_change(category_id,measurement_id=null)
    {
        $('#measurement_id').html('');$('#measurement_id').select2();
        $('.content-wrapper').block
        ({
            message: '<i class="icon-spinner9 spinner"></i>',
            overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
            css: { border: 0, padding: 0, backgroundColor: 'none' }
        }); 
        var url=base_url+'/'+master_prefix+'/items/get-measurements-with-item-category/'+category_id;
        $.ajax
        ({
            type: 'GET',
            url: url,
            dataType: "json",
            async: false,
            headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data :{'measurement_id':measurement_id},
            success: function(response)
            { 
                var obj =  $.parseJSON(JSON.stringify(response));  
                $('#measurement_id').select2("destroy"); 
                $('#measurement_id').html(obj.option);
                $('#measurement_id').select2();    
            },
            error: function (request, textStatus, errorThrown)  {  },
            complete: function() {
                $('.content-wrapper').unblock();
            },
        });
    }
 