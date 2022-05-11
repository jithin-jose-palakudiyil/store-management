var url_prefix ='indents';
$(function() 
{ 
     // Danger
    if($('.control-danger').length)
    {
        $(".control-danger").uniform({
            wrapperClass: 'border-danger-600 text-danger-800'
        });
    }
    if($('.control-success').length)
    {
        $(".control-success").uniform({
        wrapperClass: 'border-success-600 text-success-800'
        });
    }
    $('.batch_item').change(function() {
        var key = $(this).data('key');
        var numberOfChecked = $('.batch_item_key_'+key).filter(':checked').length;
        var requested_qty = $('#requested_qty_'+key).val();
        if(numberOfChecked > requested_qty){
            alert('You can select requested quantity only');
            $(this).prop('checked', false);
        }else{
            $('#approved_qty_'+key).val(numberOfChecked);   
        }          
    });
    
    $('.chkAction').change(function() {
        var key = $(this).data('cid');
        var numberOfChecked = $('.chk_'+key).filter(':checked').length;
        var ApprovedQty = $('#aQty_'+key).val();
        if(numberOfChecked > ApprovedQty){
            alert('You can select approved  quantity only');
            $(this).prop('checked', false);
            $('#BtnSub').prop('disabled', true);
            //BtnSub
        }else{
            $('#BtnSub').prop('disabled', false);
            $('#tQty_'+key).val(numberOfChecked);   
        }          
    });
    
    
    $('.tQty').keyup(function() { 
         
            var tid = $(this).data('tid');
            var val_1 = parseInt($(this).val());
            var approved_qtys = $('#aQty_'+tid).val();
                
            if(val_1 !='' && !isNaN(val_1)  && !isNaN(approved_qtys) ) { 
                 
                if(val_1 > approved_qtys){ 
                    $('#BtnSub').prop('disabled', true);
                    alert('You can add approved quantity only'); 
                } else{
                    $('#BtnSub').prop('disabled', false);   
                } 
            }
         
        });
        
        
        
        
/* ************************************************************************** */  
/* *************************** initialization ******************************* */  
/* ************************************************************************** */ 

    if($('#datatable').length)
    {   
        
        
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
        var types=$('.datatable-basic').attr('data-type');
        var i = 1;
        $('.datatable-basic').DataTable
        ({
            processing: true,
            serverSide: true, 
//            ajax: url,
            "ajax": { 
                'url': url,
                'data': { type:types},
            }, 
            "columnDefs": [
                { className: "text-center", "targets": [ 3] }
              ],
            columns: [ 
                        {
                            data: "id", sortable: true,
                            render: function (data, type, full) {  return  full.id; } 
                        },   
                          
                         
                        {
                            data: "authority_status", sortable: false,  
                            render: function (data, type, full) 
                            { 
                                if(full.authority_status=="0")  { return '<span class="label label-warning">pending</span>';  }
                                else if(full.authority_status=="1")  { return '<span class="label label-success">processed</span>';  }
                            } 
                        },  
                        {
                            data: "to_status", sortable: false,  
                            render: function (data, type, full) 
                            { 
                                if(full.to_status=="0")  { return '<span class="label label-warning">pending</span>';  }
                                else if(full.to_status=="1")  { return '<span class="label label-success">approved</span>';  }
                                else if(full.to_status=="2")  { return '<span class="label label-danger">rejected</span>';  }
                            } 
                        },
                        {
                            data: "null","searchable": false, sortable: false,
                            render: function (data, type, full)
                            {   
                                var  u =''; 
                                var show_url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+full.id+'?type='+types;
                                var icons_show='icon-eye'; var icons_show_class=' btn-primary';
                                if( typeof(if_master ) != "undefined" && if_master ==false ){
                                    if(full.authority_status==1 && full.to_status==1 && full.from_status!=1 ){
                                     icons_show='icon-pencil5';   icons_show_class=' btn-danger';
                                    }
                                    
                                }
                                u+='<a class="text-center" href="'+show_url+'"><button type="button" class="btn '+icons_show_class+' btn-icon"><i class="'+icons_show+'"></i></button></a> &nbsp;';
                              
                                if( typeof(editBtn) != "undefined" && editBtn && types == 'request_recived'&& full.authority_status=="0" && typeof(if_master) != "undefined" && if_master){
                                   var edit_url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+full.id+'/edit';
                                    u+='<a class="text-center" href="'+edit_url+'"><button type="button" class="btn btn-brown btn-icon"><i class="icon-pencil5"></i></button></a> &nbsp;';
                                } 
//                                transferBtn 
                                if( typeof(transferBtn) != "undefined" && transferBtn && types == 'request_recived' && full.authority_status==1 && full.to_warehouse==1 && full.request_to==null && full.to_status!=1 && typeof(if_master) != "undefined" && if_master){
                                   var transferred_url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+full.id+'/transfer';
                                    u+='<a class="text-center" href="'+transferred_url+'"><button type="button" class="btn btn-danger btn-icon"><i class="icon-pencil5"></i></button></a> &nbsp;';
                                }else{
                                    if( typeof(transferBtn) != "undefined" && transferBtn && types == 'request_recived' && full.authority_status==1 && full.to_warehouse!=1 && full.request_to !=null && full.to_status!=1 && typeof(if_master) != "undefined" && if_master==false){
                                        //var transferred_url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+full.id+'/store-transfer';
                                        var transferred_url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+full.id+'/transfer';
                                        u+='<a class="text-center" href="'+transferred_url+'"><button type="button" class="btn btn-danger btn-icon"><i class="icon-pencil5"></i></button></a> &nbsp;';
                                    }
                                } 
                                
                                return u;
                            } 
                        }
            ] 
        });
      
    }
    
    
    
    
    
    $("#request_to").change(function() {
        if($('option:selected', this).val().length > 0){
            $('.table_row').not(':first').remove();
            $("#item_category_1 option:selected").prop("selected", false);
            $("#item_category_1 option:first").prop("selected", "selected");
            $(".table_row:first").find('input[type=text]').val('');
            $(".table_row:first").find('.validation-error-label').remove();
            $(".ItemInformation").show();
        }else{
            $('.table_row').not(':first').remove();
            $("#item_category_1 option:selected").prop("selected", false);
            $("#item_category_1 option:first").prop("selected", "selected");
            $(".table_row:first").find('input[type=text]').val('');
            $(".table_row:first").find('.validation-error-label').remove();
            $(".ItemInformation").hide();
        }
    });
    
    $(document).on('change', '.item_category', function(){  
        var item_category = $(this).data("item_category") 
        $("#item_"+item_category).val('');
        $("#item_id_"+item_category).val('');
        $("#unit_"+item_category).val('');
        $("#QtyAvailable_"+item_category).val('');
        $("#QtyRequest_"+item_category).val('');
    });
    
    if($('.control-primary').length)
    { 
        // Primary
       $(".control-primary").uniform({
           wrapperClass: 'border-primary-600 text-primary-800'
       }); 
    }
    
    // Simple select without search
    if($('.select').length){  $('.select').select2({ minimumResultsForSearch: Infinity});}

    // Styled checkboxes and radios
    if($('.styled').length){ $('.styled').uniform();}


        /* ********************************************************************* */  
        /* ************************ item autocomplete *************************** */  
        /* ********************************************************************* */ 
    if($('.autocomplete').length){
        $(".autocomplete").autocomplete({
            source: function (request, response) { 
                //get data item number
                var item_num = this.element.data('item');
                var item_category =  $("#item_category_"+item_num).find(':selected').val()
                var request_to = $("#request_to").find(':selected').val();
                if(item_category.length > 0 && request_to.length > 0)
                {   
                    $.ajax({
                        url:base_url+'/'+master_prefix+'/indent-autocomplete', 
                        dataType: "json",
                        cache: false,
                        data: {
                            term: request.term, item_category:item_category, request_to:request_to
                        },
                        success: function (data) { 
                            if (data.length === 0) {
                                 
                                data = [{ 'label': request.term+"<span class='open_popup'  data-item='"+request.term+"' style='color: red;'> not found</span>  ", "value": request.term, "id": -1 }];
                                response(data);
                            }else{
                                var resp = $.map(data,function(obj){ 
                                   return { label: obj.name+' - '+obj.id, value: obj.id  ,"id": obj.id , 'short_code': obj.has_one_measurements.short_code,'quantity':obj.quantity } ;
                               }); 
                               response(resp);
                            }  
                        }
                    });

                }else{ return [];  }  
            },
            minLength: 1,
            select: function (event, ui) {
                var data_item = event.target.getAttribute("data-item");  
                if (ui.item.id === -1) {
                    $("#item_"+data_item).val('');
                    $("#item_id_"+data_item).val(''); 
                    return false;
                } else {
                     
                    $("#unit_"+data_item).val(ui.item.short_code);
                    $("#QtyAvailable_"+data_item).val(ui.item.quantity);
                    $("#item_"+data_item).val(ui.item.label);
                    $("#item_id_"+data_item).val(ui.item.id); 
                    return false;
                }
            },response: function(event, ui) {}
        }).data("ui-autocomplete")._renderItem = function (ul, item) {return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "</a>").appendTo(ul);};
    }   
          
         

    /* ********************************************************************* */  
    /* ************************* form validate ***************************** */  
    /* ********************************************************************* */ 
    
    
    
    if($('#_form').length){
        $("#_form").validate({
            ignore: 'input[type=hidden]', // ignore hidden fields
            errorClass: 'validation-error-label',  successClass: 'validation-valid-label',
            highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
            unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
            // Different components require proper error label placement
            errorPlacement: function(error, element) {  
                if (element.attr("name") == "request_to" ){  $("#request_to_err").html(error); } 
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'request_to':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                        'item_category[1]':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                        'item[1]':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                        'qty_request[1]':{required:true, normalizer: function(value) { return $.trim(value);  },number: true  },
                        
           }
        });  
    }   
    
    
      if($('#IndentItems').length){
        $("#IndentItems").validate({
            ignore: 'input[type=hidden]', // ignore hidden fields
            errorClass: 'validation-error-label',  successClass: 'validation-valid-label',
            highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
            unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
            // Different components require proper error label placement
            errorPlacement: function(error, element) { 
               
                if (element.hasClass("status")){ 
                    var id ='err_'+element.attr("id");
                     $("#"+id).html(error);    
                } 
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'status[0]':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                        'approved_qty[0]':{required:true, normalizer: function(value) { return $.trim(value);  },number: true  },
                        
           }
        });  
        $('.status').each(function(index)  { 
          $(this).rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
        });
        $('.approved_qty').each(function(index)  { 
          $(this).rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  },number: true  }); 
        });
        
        $('.approved_qty').keyup(function() { 
         
            var qty = $(this).data('qty');
            var val_1 = parseInt($(this).val());
            var requested_qtys = $('#requested_qty_'+qty).val();
                
            if(val_1 !='' && !isNaN(val_1)  && !isNaN(requested_qtys) ) { 
                 
                if(val_1 > requested_qtys){ 
                    alert('You can add requested quantity only'); 
                }  
            }
         
        });
        
    /* ********************************************************************* */  
    /* ************************* form submit ******************************* */  
    /* ********************************************************************* */ 
    
        $(document).on("submit","#IndentItems",function(e)
        { 
            e.preventDefault(); 
            const approved_qty = []; 
            $('.approved_qty').each(function(index)  { 
                 
                var qty = $(this).data('qty');
                var numberOfChecked = parseInt($(this).val());
                var requested_qtys = $('#requested_qty_'+qty).val();  
                if(numberOfChecked !='' && !isNaN(numberOfChecked)  && !isNaN(requested_qtys) ) {  
                    if(numberOfChecked > requested_qtys){ 
                        $(this).css("border", "#f44336 solid 1px"); 
                        approved_qty.push(index);  
                    }  
                }
            }); 
            if (approved_qty.length == 0) {$('#errors').html('');$( "#IndentItems" )[0].submit();      return true;}
            else{ $('#errors').html('<label  class="validation-error-label" for="">You can add requested quantity only.</label>'); return false; }
            
        }); 
     
     
     
     
    } 
    
    
    
    
    
//       $('.tQty').keyup(function() { 
//         
//            var id = $(this).data('tid');
//            var Transfer_Qty = parseInt($(this).val());
//            var Approved_Qty = parseInt($('#aQty_'+id).val());
//                
//            if(Transfer_Qty !='' && !isNaN(Transfer_Qty)  && !isNaN(Approved_Qty) ) { 
//                if(Transfer_Qty > Approved_Qty){ 
//                    alert('You can transfer approved quantity or less only'); 
//                }  
//            }
//         
//        });
    
    
    
    if($('#store_update').length){
        
        var is_transferred= $('.is_transferred').first().data('id');
        
        $("#store_update").validate({
            ignore: 'input[type=hidden]', // ignore hidden fields
            errorClass: 'validation-error-label',  successClass: 'validation-valid-label',
            highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
            unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
            // Different components require proper error label placement
            errorPlacement: function(error, element) { 
               
                if (element.hasClass("is_transferred")){ 
                    var id ='err_'+element.attr("data-id"); 
                    $("#"+id).html(error);    
                } 
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'is_transferred[is_transferred]':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                         
                        
           }
        });  
        $('.is_transferred').each(function(index)  { 
          $(this).rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
        });
       
    } 
    
    if($('#store_from_update').length){
        
        var is_recived= $('.is_recived').first().data('id');
        
        $("#store_from_update").validate({
            ignore: 'input[type=hidden]', // ignore hidden fields
            errorClass: 'validation-error-label',  successClass: 'validation-valid-label',
            highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
            unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
            // Different components require proper error label placement
            errorPlacement: function(error, element) { 
               
                if (element.hasClass("is_recived")){ 
                    var id ='errr_'+element.attr("data-id"); 
                    $("#"+id).html(error);    
                } 
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'is_recived[is_recived]':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                         
                        
           }
        });  
        $('.is_recived').each(function(index)  { 
          $(this).rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
        });
       
    } 
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /* ********************************************************************* */  
    /* **************************** add item ******************************* */  
    /* ********************************************************************* */ 

    $(document.body).on("click",".add_item",function()
    { 
         
        $(".table_row:last").clone().hide().appendTo('.table').show('slow'); //taken clone of the last
        $(".table_row:last").find('.item_category option:selected').removeAttr("selected");
        $(".table_row:last").find('input[type=text]').val('');
        $(".table_row:last").find('.validation-error-label').remove();
        $('.table_row').each(function(index)
        {
            index = parseInt(index)+1; 
            $(this).attr('id','table_row_'+index);
            $(this).find(".item_category").attr('name','item_category['+(index)+']');
            $(this).find(".item").attr('name','item['+(index)+']');
            $(this).find(".item").attr('id','item_'+(index)); 
            $(this).find(".unit").attr('id','unit_'+(index));
            $(this).find(".item_id").attr('name','item_id['+(index)+']');
            $(this).find(".item_id").attr('id','item_id_'+(index));
            $(this).find(".QtyAvailable").attr('id','QtyAvailable_'+(index));
            $(this).find(".qty_request").attr('id','QtyRequest_'+(index));
            $(this).find(".qty_request").attr('name','qty_request['+(index)+']');
            $(this).find(".item").attr("data-item", index);
            $(this).find(".item_category").attr("data-item_category", index);
            $(this).find(".item_category").attr('id','item_category_'+(index)); 
            
            if(index !=1){ $(this).find(".remove_item").show(); } 
            $(this).find(".remove_item").attr('data-row',index); 
                 
            $(this).find(".item_category").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
            $(this).find(".item").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  }  }); 
            $(this).find(".qty_request").rules("add",{  required:true, normalizer: function(value) { return $.trim(value);  },number: true  }); 
                 
               
            
            
            
            
            
            
            
            
            
            
            
        /* ********************************************************************* */  
        /* ************************ item autocomplete *************************** */  
        /* ********************************************************************* */ 

        $(this).find(".autocomplete").autocomplete({
            source: function (request, response) { 
                //get data item number
                var item_num = this.element.data('item');
                var item_category =  $("#item_category_"+item_num).find(':selected').val()
                var request_to = $("#request_to").find(':selected').val();
                if(item_category.length > 0 && request_to.length > 0)
                {   
                    $.ajax({
                        url:base_url+'/'+master_prefix+'/indent-autocomplete', 
                        dataType: "json",
                        cache: false,
                        data: {
                            term: request.term, item_category:item_category, request_to:request_to
                        },
                        success: function (data) { 
                            if (data.length === 0) {
                                 
                                data = [{ 'label': request.term+"<span class='open_popup'  data-item='"+request.term+"' style='color: red;'> not found</span>  ", "value": request.term, "id": -1 }];
                                response(data);
                            }else{
                                var resp = $.map(data,function(obj){ 
                                   return { label: obj.name+' - '+obj.id, value: obj.id  ,"id": obj.id , 'short_code': obj.has_one_measurements.short_code,'quantity':obj.quantity } ;
                               }); 
                               response(resp);
                            }  
                        }
                    });

                }else{ return [];  }  
            },
            minLength: 1,
            select: function (event, ui) {
                var data_item = event.target.getAttribute("data-item");  
                if (ui.item.id === -1) {
                    $("#item_"+data_item).val('');
                    $("#item_id_"+data_item).val(''); 
                    return false;
                } else {
                    
                    $("#unit_"+data_item).val(ui.item.short_code);
                    $("#QtyAvailable_"+data_item).val(ui.item.quantity);
                    $("#item_"+data_item).val(ui.item.label);
                    $("#item_id_"+data_item).val(ui.item.id); 
                    return false;
                }
            },response: function(event, ui) {}
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
                    $(this).find(".item_category").attr('name','item_category['+(index)+']');
                    $(this).find(".item").attr('name','item['+(index)+']');
                    $(this).find(".item").attr('id','item_'+(index)); 
                    $(this).find(".unit").attr('id','unit_'+(index));
                    $(this).find(".item_id").attr('name','item_id['+(index)+']');
                    $(this).find(".item_id").attr('id','item_id_'+(index));
                    $(this).find(".QtyAvailable").attr('id','QtyAvailable_'+(index));
                    $(this).find(".qty_request").attr('id','QtyRequest_'+(index));
                    $(this).find(".qty_request").attr('name','qty_request['+(index)+']');
                    $(this).find(".item").attr("data-item", index);
                    $(this).find(".item_category").attr("data-item_category", index);
                    $(this).find(".item_category").attr('id','item_category_'+(index));
                    
                    if(index !=1){ $(this).find(".remove_item").show(); } 
                    $(this).find(".remove_item").attr('data-row',index); 

                });
            }     
        });    
        
}); 
