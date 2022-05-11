var url_prefix ='users';
$(function() 
{  
    if($('.control-primary').length)
    { 
        // Primary
       $(".control-primary").uniform({
           wrapperClass: 'border-primary-600 text-primary-800'
       }); 
    }
    if($('.styled').length)
    { 
         // Styled checkboxes and radios
        $('.styled').uniform();
    
    }
        $(document.body).on("change","#role",function()
        {   

            var role =$(this).val(); 
            get_store(role);

        });
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
                            "render": function() {
                                return i++;
                            }
                    },  
                    {
                        data: "name", sortable: true,
                        render: function (data, type, full) {  return  full.name; } 
                    }, 
                    
                    {
                        data: "username", sortable: false,
                        render: function (data, type, full) {  return  full.username; } 
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
                                var delete_button ='onclick="return ConfirmDelete('+full.id+')"';
                                u+='<button type="button" '+delete_button+' class="btn btn-danger btn-icon"><i class=" icon-trash"></i></button>&nbsp;&nbsp;';
                            }
                            if( typeof(assigningBtn) != "undefined" && assigningBtn){
                                var role_permissions=base_url+'/'+master_prefix+'/'+url_prefix+'/assigning-module-permissions/'+full.id;
                                u+='<a class="text-center" href="'+role_permissions+'"><button type="button" class="btn  bg-purple-400 btn-ladda btn-icon"><i class="icon-key"></i></button></a> &nbsp;';
                            }
                            return u;
                            
//                                var delete_button ='onclick="return ConfirmDelete('+full.id+')"';
//                                var edit_url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+full.id+'/edit';
//                                var role_permissions=base_url+'/'+master_prefix+'/'+url_prefix+'/assigning-module-permissions/'+full.id;
//                                var  u ='<a class="text-center" href="'+edit_url+'"><button type="button" class="btn btn-primary btn-icon"><i class="icon-pencil5"></i></button></a> &nbsp;'+
//                                        '<button type="button" '+delete_button+' class="btn btn-danger btn-icon"><i class=" icon-trash"></i></button>&nbsp;&nbsp;'+
//                                        '<a class="text-center" href="'+role_permissions+'"><button type="button" class="btn  bg-purple-400 btn-ladda btn-icon"><i class="icon-key"></i></button></a> &nbsp;'+
//                                        '</div>';                     
//
//                                
//                                return u;
//                            return u;
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

        


        $("#_form").validate({
            ignore: 'input[type=hidden]', // ignore hidden fields
            errorClass: 'validation-error-label',  successClass: 'validation-valid-label',
            highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
            unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
            // Different components require proper error label placement
            errorPlacement: function(error, element) {  
                if (element.attr("name") == "status" ){  $("#status_err").html(error); }
                if (element.attr("name") == "store_id" ){  $("#store_id_err").html(error); }
                
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'name':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                        'username':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                        'password':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                        'confirm_password':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255,equalTo : '[name="password"]' },
                        'status':{required:true, normalizer: function(value) { return $.trim(value);  } },
//                        'thumb_icon':  { required:true,   fileType: { types: ["jpg", "jpeg", "png", "gif"] }, maxFileSize: { "unit": "MB",  "size": 2  }, }, 
            },
            messages: { confirm_password:{ equalTo:" Enter Confirm Password Same as Password"} }
        }); 
        if($('input[name="HdnEdit"]').length)
        {
            $('input[name="password"]').rules('remove', 'required');
            $('input[name="confirm_password"]').rules('remove', 'required');
        }
    }
     
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
    /* --------------------------- change -------------------------------- */ 
    /* ------------------------------------------------------------------------- */ 
  
    function  get_store(role,edit=null)
    {
        $('.content-wrapper').block
        ({
            message: '<i class="icon-spinner9 spinner"></i>',
            overlayCSS: { backgroundColor: '#fff',  opacity: 0.8, cursor: 'wait' },
            css: { border: 0, padding: 0, backgroundColor: 'none' }
        });
        if(role=='store'){
            
        var url=base_url+'/'+master_prefix+'/'+url_prefix+'/get-store/'+role;
        $.ajax
            ({
                type: 'GET',
                url: url,
                dataType: "json",
                async: false,
                headers: {  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data:{'edit':edit},
                success: function(response)
                { 
                    var obj =  $.parseJSON(JSON.stringify(response));  
                     
                    if(obj.html){ 
                        $('#store_div').html(obj.html); 
                        if($('.styled').length) { $('.styled').uniform(); }
                        $(".store_id").rules("add", { required:true, normalizer: function(value) { return $.trim(value);  } });

                    }
                },
                error: function (request, textStatus, errorThrown)  {  },
                complete: function() {
                    $('.content-wrapper').unblock();
                },
        });

        }else
        {
            $('#store_div').html('');
            $('.content-wrapper').unblock();
        }


    }