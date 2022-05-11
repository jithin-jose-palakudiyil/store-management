var url_prefix ='items';
$(function() 
{  
    if($('.control-primary').length)
    { 
        // Primary
       $(".control-primary").uniform({
           wrapperClass: 'border-primary-600 text-primary-800'
       }); 
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
            { className: "text-center", "targets": [ 4,5] }
          ],
        columns: [ 
                    {
                        data: "id", sortable: true,
                        render: function (data, type, full) {  return  full.id; } 
                    },   
                    {
                        data: "name", sortable: true,
                        render: function (data, type, full) {  return  full.name; } 
                    }, 
                    {
                        data: "quantity", sortable: true,
                        render: function (data, type, full) { 
                            var Q =full.quantity+' '+full.has_one_measurements.short_code
                            return  Q; 
                        } 
                    }, 
                    {
                        data: "null", sortable: false,
                        render: function (data, type, full) {  return  full.has_one_item_category.name; } 
                    },
                    {
                        data: "status", sortable: true,  
                        render: function (data, type, full) 
                        { 
                            if(full.status=="1")  { return '<span class="label label-success">Active</span>';  }
                            else if(full.status=="2")  { return '<span class="label label-warning">Inactive</span>';  }
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
                            
                            if( typeof(deleteBtn) != "undefined" && deleteBtn){
                                
                                var delete_button =''
                                if(full.has_unique_id != 1){
                                    delete_button ='onclick="return ConfirmDelete('+full.id+')"';
                                }else{
                                    delete_button ='onclick="return viewDelete('+full.id+')"'; 
                                }
                              
//                              
                                u+='<button type="button" '+delete_button+' class="btn btn-danger btn-icon"><i class=" icon-trash"></i></button>&nbsp;&nbsp;';
                            }
                            
                            if(full.has_one_item_category.allow_usage==1){ 
//                                if( typeof(deleteBtn) != "undefined" && deleteBtn){
                                    var allow_usage_button ='onclick="return AllowUsage('+full.id+')"';
                                    u+='<button type="button" '+allow_usage_button+' class="btn btn-brown btn-icon"><i class="icon-lab"></i></button>&nbsp;&nbsp;';
//                                } 
                            }
                            if( typeof(batchBtn) != "undefined" && batchBtn){
                                var batch_button ='onclick="return viewBatch('+full.id+')"';
                                u+='<button type="button" '+batch_button+' class="btn btn-info btn-icon"><i class=" icon-eye"></i></button>&nbsp;&nbsp;';
                            }
                            
                            
                            
                            
                            
                            
                            
                            
                            if(full.has_unique_id == 1){
                                var allow_uid_view ='onclick="return UidView('+full.id+')"';
                                u+='<button type="button" '+allow_uid_view+' class="btn btn btn-info btn-icon"><i class=" icon-tree6"></i></button>&nbsp;&nbsp;';
                                
                                
                                var allow_barcode_view ='onclick="return BarcodeView('+full.id+')"';
                                u+='<button type="button" '+allow_barcode_view+' class="btn btn btn-brown btn-icon"><i class="  icon-barcode2"></i></button>&nbsp;&nbsp;';
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
            $(document.body).on("change","#category_id",function()
            {   
                
                var category_id =$(this).val(); 
                category_change(category_id);

            });
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
                if (element.attr("name") == "status" ){  $("#status_err").html(error); }
                else if (element.attr("name") == "measurement_id" ){  $("#measurement_id_err").html(error); }
                else if (element.attr("name") == "category_id" ){  $("#category_id_err").html(error); }
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'name':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                        'category_id':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                        'measurement_id':{required:true, normalizer: function(value) { return $.trim(value);  }  },
                        'status':{required:true, normalizer: function(value) { return $.trim(value);  } },
           }
        });  
    }
  

    $(document).on("submit","#usageEntry",function(e) {
        //prevent Default functionality  
        e.preventDefault();
        var usage_date=document.getElementById("usage_date").value;  
        var usage_quantity=document.getElementById("usage_quantity").value;
        var item_id=document.getElementById("item_id").value; 
        var unique=document.getElementById("unique").value;
        var unique_id=null;
        if (document.getElementById('unique_id') != null) {
             unique_id=document.getElementById("unique_id").value;
        }
         
        
        
        
       
        $('#usage_date-error').remove(); $('#usage_quantity-error').remove();
        var quantity_total = false;  var quantity_form= false; var date_form = false;  var unique_form = true;  
        var QuantityBtn = document.getElementById("QuantityBtn").value;
        var Quantity = QuantityBtn-usage_quantity;
        
        if (Quantity < 0){  quantity_total = false;
            $(this).find('#usage_quantity').after("<label id='usage_quantity-error' class='validation-error-label'>This remaining quantity with usage quantity not matching.</label>");
            return false;
        }else{quantity_total = true;}
        
        if ( usage_date==null || usage_date==""){  date_form = false;
           $(this).find('#usage_date').after("<label id='usage_date-error' class='validation-error-label'>This field is required.</label>");
        }else{date_form = true;}
        
       
        
        if ( unique!=null && unique==1){ 
             
             if ( unique_id.length >0){  
                 $(this).find('#unique_id-error').remove();
             }else{
                $(this).find('#unique_id').after("<label id='unique_id-error' class='validation-error-label'>This field is required.</label>");
                unique_form = false;  
             }
           
        }else{unique_form = true;}
        
        if ( (usage_quantity==null || usage_quantity=="") ||  usage_quantity == 0 ){  quantity_form = false; 
            $(this).find('#usage_quantity').after("<label id='usage_quantity-error' class='validation-error-label'>This field is required.</label>");
            return false;  
        }else{quantity_form = true;}
           
        if(quantity_total && date_form && quantity_form && unique_form){
           var data;
           if(unique==1){
               data={'item_id':item_id,'usage_date':usage_date,'usage_quantity':usage_quantity,'unique_id':unique_id};
           }else{
             data={'item_id':item_id,'usage_date':usage_date,'usage_quantity':usage_quantity} ; 
           }
            $('.content-wrapper').block
            ({
                message: '<i class="icon-spinner9 spinner"></i>',
                overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
                css: { border: 0, padding: 0, backgroundColor: 'none' }
            });

            var url_form=base_url+'/'+master_prefix+'/'+url_prefix+'/store-usage';
            $.ajax
            ({
                type: 'POST',
                url: url_form,
                dataType: "json",
                async: false,
                headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data :data,
                success: function(response)
                { 
                    document.getElementById("usage_date").value = "";$("#usage_date").datepicker("refresh");
                    if ( unique!=1){
                         document.getElementById("usage_quantity").value = "";
                    }
                   if (document.getElementById('unique_id') != null) {
                       document.getElementById("unique_id").value = "";
                   }
                    
                    var obj =  $.parseJSON(JSON.stringify(response));
                    if(obj.list){ $('#usageListDiv').html(obj.list);  }
                    if(obj.message){ $('#MsgDiv').html(obj.message); }
                    if(obj.remaining_quantity){ $('#remaining_quantity').html(obj.remaining_quantity); document.getElementById("QuantityBtn").value = obj.quantity; }
                },
                error: function (request, textStatus, errorThrown)  {  },
                complete: function() {
                    $('.content-wrapper').unblock();
                },
        });
        
        
        }
        


        return false; 
    });

        $(document.body).on("change",".Select_All",function()
        {
 
             if($(this).is(':checked')){ 
                $(".uid_cbk").each(function() {
                    var _id = '#'+$(this).attr('id');
                    $(_id).prop('checked',true);
                    $.uniform.update(_id); 
                }); 
            }else{
                $(".uid_cbk").each(function() {
                    var _id = '#'+$(this).attr('id');
                    $(_id).prop('checked',false);
                    $.uniform.update(_id); 
                }); 
            }
           
        });

        $(document.body).on("click","#DeleteBtn",function()
        {
            var ht= '';
            var uid=$('#DeleteTxt').val();
            
            if(uid.length >0){
                var item_id=$('#item_id').val();
                $("#DeleteBtn").prop("disabled", true);
                ConfirmDelete(item_id,'uid',uid);
                document.getElementById("noty_layout__center").style.zIndex = "99999";
            }else{
               ht= '<div class="validation-error-label" style="display: inline-block;">this field is required</div>';
            }
            $('#DeleteErr').html(ht); 
        });
     
     
     
     
     
     
     
     
     
});

 
/* ************************************************************************** */  
/* **************************** Generate Slug ******************************* */  
/* ************************************************************************** */ 

//function generate_slug(str) 
//{
//    var $slug = '';
//    var trimmed = $.trim(str);
//    $slug = trimmed.replace(/ /g, '-').
//            replace(/\s*:|\s+(?=\s)/g, "").
//            replace(/-+/g, '-').
//            replace(/^-|-$/g, '');
//    return $slug.toLowerCase();
//}


/* ************************************************************************** */  
/* **************************** Delete Entry ******************************** */  
/* ************************************************************************** */ 

 function ConfirmDelete(id,type='sng',unique_id=null)
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
                        if($("#DeleteBtn").length>0){
                            $("#DeleteBtn").prop("disabled", false); 
                        }
                        notyConfirm.close();
                    }),

                    Noty.button('Yes <i class="icon-paperplane ml-2"></i>', 'btn bg-slate-600 ml-1', function () {
                         var url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+id;   
                        var data ={'id':id,'type':type,};
                        if(type !=null && unique_id !=null ){
                             data ={'id':id,'type':type,'unique_id':unique_id};
                        }
                        $.ajax
                        ({
                            type: 'DELETE',
                            url: url,
                            dataType: "json",
                            async: false,
                            headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data :data,
                            success: function(response){ 
//                                $("#DeleteBtn").prop("disabled", false);
                                window.location.reload();
                            },
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
    /* ------------------------------------------------------------------------- */  
    /* --------------------------- change -------------------------------- */ 
    /* ------------------------------------------------------------------------- */ 
  
    function  category_change(category_id,measurement_id=null)
    {
        
        $('#measurement_id').html('');$('#measurement_id').select2();
         
        $('.content-wrapper').block
        ({
            message: '<i class="icon-spinner9 spinner"></i>',
            overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
            css: { border: 0, padding: 0, backgroundColor: 'none' }
        });

        var url=base_url+'/'+master_prefix+'/'+url_prefix+'/get-measurements-with-item-category/'+category_id;
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
 
    /* ------------------------------------------------------------------------ */  
    /* --------------------------- Allow Usage -------------------------------- */ 
    /* ------------------------------------------------------------------------- */ 
  
   function  AllowUsage(item_id)
   {
       $('#usage_model').html('');
         
        $('.content-wrapper').block
        ({
            message: '<i class="icon-spinner9 spinner"></i>',
            overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
            css: { border: 0, padding: 0, backgroundColor: 'none' }
        });

        var url=base_url+'/'+master_prefix+'/'+url_prefix+'/get-usage-model/'+item_id;
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
//                    $('#measurement_id').select2("destroy"); 
                    $('#usage_model').html(obj.html);
                     if($('.datepicker-menus').length) 
                    { 
                        $(".datepicker-menus").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: 'dd-mm-yy'

                        });
                    }
//                    if(obj.unique){
//                        console.log('val');
//                    }
                    
//                    if($('#usageEntry').length)
//                    {
//                        $('#usageEntry').submit(false); 
//                    }
                    
                    $('#modal_item_usage').modal('show');
//                    $('#measurement_id').select2();    
                },
                error: function (request, textStatus, errorThrown)  {  },
                complete: function() {
                    $('.content-wrapper').unblock();
                },
        });
   }
   
    /* ------------------------------------------------------------------------ */  
    /* --------------------------- viewBatch -------------------------------- */ 
    /* ------------------------------------------------------------------------- */ 
  
   function viewBatch(item_id){
     $('#batch_model').html('');
         
        $('.content-wrapper').block
        ({
            message: '<i class="icon-spinner9 spinner"></i>',
            overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
            css: { border: 0, padding: 0, backgroundColor: 'none' }
        });

        var url=base_url+'/'+master_prefix+'/'+url_prefix+'/get-batch-model/'+item_id;
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
                    $('#batch_model').html(obj.html); 
                    $('#modal_item_batch').modal('show');   
                },
                error: function (request, textStatus, errorThrown)  {  },
                complete: function() {
                    $('.content-wrapper').unblock();
                },
        });
   }
   
   /* ------------------------------------------------------------------------ */  
    /* --------------------------- viewDelete -------------------------------- */ 
    /* ------------------------------------------------------------------------- */ 
  
   function viewDelete(item_id){
     $('#delete_model').html('');
         
        $('.content-wrapper').block
        ({
            message: '<i class="icon-spinner9 spinner"></i>',
            overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
            css: { border: 0, padding: 0, backgroundColor: 'none' }
        });

        var url=base_url+'/'+master_prefix+'/'+url_prefix+'/get-delete-model/'+item_id;
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
                    $('#delete_model').html(obj.html); 
                    $('#modal_item_delete').modal('show');   
                },
                error: function (request, textStatus, errorThrown)  {  },
                complete: function() {
                    $('.content-wrapper').unblock();
                },
        });
   }
  

    /* ------------------------------------------------------------------------ */  
    /* --------------------------- UidView -------------------------------- */ 
    /* ------------------------------------------------------------------------- */ 
   
   function UidView(item_id){
     $('#delete_model').html('');
         
        $('.content-wrapper').block
        ({
            message: '<i class="icon-spinner9 spinner"></i>',
            overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
            css: { border: 0, padding: 0, backgroundColor: 'none' }
        });

        var url=base_url+'/'+master_prefix+'/'+url_prefix+'/get-uid-model/'+item_id;
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
                    $('#delete_model').html(obj.html); 
                    $('#modal_item_delete').modal('show');   
                },
                error: function (request, textStatus, errorThrown)  {  },
                complete: function() {
                    $('.content-wrapper').unblock();
                },
        });
   }
   /* ------------------------------------------------------------------------ */  
    /* --------------------------- BarcodeView -------------------------------- */ 
    /* ------------------------------------------------------------------------- */ 
  
   function BarcodeView(item_id)
   {
     $('#delete_model').html('');
         
        $('.content-wrapper').block
        ({
            message: '<i class="icon-spinner9 spinner"></i>',
            overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
            css: { border: 0, padding: 0, backgroundColor: 'none' }
        });

        var url=base_url+'/'+master_prefix+'/'+url_prefix+'/get-barcode-model/'+item_id;
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
                    $('#delete_model').html(obj.html); 
                    if($('.control-primary').length)
                    { 
                        // Primary
                       $(".control-primary").uniform({
                           wrapperClass: 'border-primary-600 text-primary-800'
                       }); 
                    }
                    
                    
                    
                    
                    
                    
        $("#barcode_view_form").validate({
            ignore: 'input[type=hidden]', // ignore hidden fields
            errorClass: 'validation-error-label',  successClass: 'validation-valid-label',
            highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
            unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
            // Different components require proper error label placement
            errorPlacement: function(error, element) {  
                if (element.attr("name") == "uid[]" ){  $("#uid_err").html(error); } 
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'uid[]':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                         
           }
        }); 
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    $('#modal_item_delete').modal('show');   
                },
                error: function (request, textStatus, errorThrown)  {  },
                complete: function() {
                    $('.content-wrapper').unblock();
                },
        });
   }
   