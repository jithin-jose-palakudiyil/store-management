var url_prefix ='licence-renewal';
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
            { className: "text-center", "targets": [ 2 ] }
          ],
        columns: [ 
                      
                    {
                       data: "null","searchable": false, sortable: false,
                        render: function (data, type, full) {  return  full.has_one_batch_item.has_one_item.name+' - '+full.has_one_batch_item.unique_id; } 
                    }, 
                    {
                        data: "expiry_date","searchable": false, sortable: false,
                        render: function (data, type, full) {  return  full.expiry_date; } 
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
                                var delete_button ='onclick="return ConfirmDelete('+full.id+')"';
                                u+='<button type="button" '+delete_button+' class="btn btn-danger btn-icon"><i class=" icon-trash"></i></button>&nbsp;&nbsp;';
                            }
                            if( typeof(updateBtn) != "undefined" && updateBtn){
                                var update_button ='onclick="return ConfirmUpdate('+full.id+')"';
                                u+='<button type="button" '+update_button+' class="btn bg-violet btn-icon"><i class="icon-bucket"></i></button>&nbsp;&nbsp;';
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
         $( "#item_name" ).autocomplete({
  
                source: function(request, response) {
                    $.ajax({
                    url:base_url+'/'+master_prefix+'/licence-renewal-autocomplete', 
                    
                    data: {
                            term : request.term
                     },
                    dataType: "json",
                    success: function(data){
                       var resp = $.map(data,function(obj){ 
                           return { label: obj.name+' - '+obj.unique_id, name: obj.name, value: obj.id ,'unique_id':obj.unique_id  ,'has_licence_renewal':obj.has_licence_renewal   } ;
                       }); 

                       response(resp);
                    },
                    
                });
            },select: function (event, ui) {   
                    $("#unique_id").val(ui.item.unique_id); 
                    $("#item_id").val(ui.item.value); 
                    $("#item_name").val(ui.item.name); 
                    if(ui.item.has_licence_renewal){ $("#unique_id").attr('style',  'color:red'); }else{$("#unique_id").attr('style',  'color:#333'); }
                    return false;
            },
            response: function(event, ui) {
                if (!ui.content.length) { 
                    var noResult = { value:"",label:"No results found with "+event.target.value };
                    ui.content.push(noResult);
                }
        },
            minLength: 2
         })
          .data("ui-autocomplete")._renderItem = function (ul, item) {
            var CustomStyle = '';
            if(item.has_licence_renewal){
                CustomStyle = 'style="color: red;"'; 
            }
             return $("<li></li>").data("item.autocomplete", item).append("<a "+CustomStyle+">" + item.label + "</a>").appendTo(ul);
         };
        
         
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
                else if (element.attr("name") == "calibration_type_id" ){  $("#calibration_type_id_err").html(error); }
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'item_name':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                        'item_id':{required:true, normalizer: function(value) { return $.trim(value);  },number: true },
                        'licence_no':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        'expiry_date':{required:true, normalizer: function(value) { return $.trim(value);  } },
                        'name':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                        'contact_number':{required:true, normalizer: function(value) { return $.trim(value);  },number: true },
                        'contact_email':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                        
            }
        }); 
 
        
        
        if($('.datepicker-menus').length) 
        { 
            $(".datepicker-menus").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd'

            });
        }
    }
     
     
     
     
      $(document).on("submit","#statusUpdate",function(e) {
        //prevent Default functionality  
        e.preventDefault();
         
        var renewed_date    =   document.getElementById("renewed_date").value;
        var status  =   document.getElementById("status").value; 
        var comments=   document.getElementById("comments").value; 
        var LicenceRenewal_id  =   document.getElementById("LicenceRenewal_id").value; 
        
        
        $('#date_err-error').remove(); $('#status_err-error').remove(); 
        var renewed_date_err = false;  var status_err = false; 
        if ( renewed_date==null || renewed_date==""){  renewed_date_err = false;
           $(this).find('#renewed_date_err').after("<label id='date_err-error' class='validation-error-label'>This field is required.</label>");
        }else{renewed_date_err = true;}
        
        if ( status==null || status==""){  status_err = false;
           $(this).find('#status_err').after("<label id='status_err-error' class='validation-error-label'>This field is required.</label>");
        }else{status_err = true;}

        if( renewed_date_err && status_err){
//           
            $('.content-wrapper').block
            ({
                message: '<i class="icon-spinner9 spinner"></i>',
                overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
                css: { border: 0, padding: 0, backgroundColor: 'none' }
            });
//
            var url_form=base_url+'/'+master_prefix+'/'+url_prefix+'/licence-renewal-status-update';
            $.ajax
            ({
                type: 'POST',
                url: url_form,
                dataType: "json",
                async: false,
                headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data :{'renewed_date':renewed_date,'status':status,'comments':comments,'LicenceRenewal_id':LicenceRenewal_id},
                success: function(response)
                { 
//                    document.getElementById("usage_date").value = "";$("#usage_date").datepicker("refresh");
//                    document.getElementById("usage_quantity").value = "";
                    var obj =  $.parseJSON(JSON.stringify(response));
//                    if(obj.list){ $('#usageListDiv').html(obj.list);  }
                    if(obj.html){ 
                        $('#FormDiv').html(obj.html);
                        $('.select').select2({ minimumResultsForSearch: Infinity}); 
                        $('.styled').uniform();
                    }else{
                     $('#FormDiv').html('');   
                    }
                    
                    
                    
                    if(obj.message){ $('#MsgDiv').html(obj.message); }
//                    if(obj.remaining_quantity){ $('#remaining_quantity').html(obj.remaining_quantity); document.getElementById("QuantityBtn").value = obj.quantity; }
                },
                error: function (request, textStatus, errorThrown)  {  },
                complete: function() {
                    $('.content-wrapper').unblock();
                },
        });
//        
//        
        }
        


        return false; 
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
 /* ------------------------------------------------------------------------- */  
    /* --------------------------- Update -------------------------------- */ 
    /* ------------------------------------------------------------------------- */ 
      
    function ConfirmUpdate(item_id)
    {
       $('#modal_update').html('');
         
        $('.content-wrapper').block
        ({
            message: '<i class="icon-spinner9 spinner"></i>',
            overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
            css: { border: 0, padding: 0, backgroundColor: 'none' }
        });

        var url=base_url+'/'+master_prefix+'/'+url_prefix+'/get-update-model/'+item_id;
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
                    $('#modal_update').html(obj.html); 
                    $('.select').select2({ minimumResultsForSearch: Infinity}); 
                    $('.styled').uniform(); 
                    if($('.datepicker-menus').length) 
                    { 
                        $(".datepicker-menus").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: 'yy-mm-dd'

                        });
                    }
                    $('#modal_update_div').modal('show');
//                    $('#measurement_id').select2();    
                },
                error: function (request, textStatus, errorThrown)  {  },
                complete: function() {
                    $('.content-wrapper').unblock();
                },
        });
   }