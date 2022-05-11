var url_prefix ='gate-pass-m';
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
        var breakage=$('.datatable-basic').attr('data-breakage');
        var i = 1;
        $('.datatable-basic').DataTable
        ({
          processing: true,
          serverSide: true,  
          "ajax": { 'url': url, 'data': {'breakage': breakage} },
    
//          ajax: url,
          "columnDefs": [
              { className: "text-center", "targets": [ 4 ] }
            ],
          columns: [   
                      {
                          data: "id",
                          render: function (data, type, full) {  return  full.id; } 
                      },
                      {
                          data: "name",
                          render: function (data, type, full) {  return  full.name; } 
                      },
                      {
                          data: "email",
                          render: function (data, type, full) {  return  full.email; } 
                      },
                      {
                          data: "contact_number",
                          render: function (data, type, full) {  return  full.contact_number; } 
                      },
                      {
                          data: "status", sortable: true,  
                          render: function (data, type, full) 
                          { 
                              if(full.status=="1")  { return '<span class="label label-success">closed</span>';  }
                              else if(full.status=="0")  { return '<span class="label label-warning">open</span>';  }
                          } 
                      },  
                      {
                          data: "is_breakage ", sortable: true,  
                          render: function (data, type, full) 
                          { 
                              if(full.is_breakage=="1")  { return '<span class="label label-success">closed</span>';  }
                              else if(full.is_breakage=="0")  { return '<span class="label label-warning">open</span>';  }
                              else if(full.is_breakage=="2")  { return '<span class="label label-default">not closed</span>';  }
                          } 
                      },  
                      {
                          data: "null","searchable": false, sortable: false,
                          render: function (data, type, full)
                          {   
                              var  u ='';
                              if( typeof(editBtn) != "undefined" && editBtn)
                              {
                                  
//                                  var delete_button ='onclick="return PopUp('+full.id+')"';
//                                  u+='<button type="button" '+delete_button+' class="btn btn-primary btn-icon"><i class=" icon-eye"></i></button>&nbsp;&nbsp;';
                                  
                                  var edit_url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+full.id;
                                  u+='<a class="text-center" href="'+edit_url+'"><button type="button" class="btn btn-primary btn-icon"><i class="icon-eye"></i></button></a> &nbsp;';
                                  
                                  var print_url=base_url+'/'+master_prefix+'/'+url_prefix+'/'+full.id+'/edit';
                                  u+='<a class="text-center" href="'+print_url+'" target="_blank"><button type="button" class="btn btn-default btn-icon"><i class=" icon-printer"></i></button></a> &nbsp;';
                                  
                                  
                              }
//
//                              if( typeof(deleteBtn) != "undefined" && deleteBtn){
//                                  var delete_button ='onclick="return ConfirmDelete('+full.id+')"';
//                                  u+='<button type="button" '+delete_button+' class="btn btn-danger btn-icon"><i class=" icon-trash"></i></button>&nbsp;&nbsp;';
//                              }
//                              if( typeof(updateBtn) != "undefined" && updateBtn){
//                                  var update_button ='onclick="return ConfirmUpdate('+full.id+')"';
//                                  u+='<button type="button" '+update_button+' class="btn bg-violet btn-icon"><i class="icon-bucket"></i></button>&nbsp;&nbsp;';
//                              }
                              //
                              return u;
                          } 
                      }
          ] 
      });
        
    }
    
    /* ************************************************************************** */  
    /* **************************** validate form ******************************* */  
    /* ************************************************************************** */ 
    if($('.datepicker-menus').length) 
    { 
        $(".datepicker-menus").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy'

        });
    }
    if($('.select').length)  { $('.select').select2({ minimumResultsForSearch: Infinity}); }
    if($('.styled').length) { $('.styled').uniform(); }
    
    if($('#_form').length)
    {  
 
        $("#_form").validate({
            ignore: 'input[type=hidden]', // ignore hidden fields
            errorClass: 'validation-error-label',  successClass: 'validation-valid-label',
            highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
            unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
            errorPlacement: function(error, element) {  
                if (element.attr("name") == "status" ){  $("#status_err").html(error); }
                else if (element.attr("name") == "maintenance_type_id" ){  $("#maintenance_type_id_err").html(error); }
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'pass_date':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 }, 
                        'name':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                        'contact_number':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 12 },
                        'email':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                         
                        
                    }
        }); 
         
       
    }
     
    if($('#_formUpdate').length)
    {
         $("#_formUpdate").validate({
            ignore: 'input[type=hidden]', // ignore hidden fields
            errorClass: 'validation-error-label',  successClass: 'validation-valid-label',
            highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
            unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
            errorPlacement: function(error, element) {  
                if (element.attr("name") == "is_breakage" ){  $("#is_breakage_err").html(error); } 
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'is_breakage':{required:true, normalizer: function(value) { return $.trim(value);  } }, 
                          
                        
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
  
   