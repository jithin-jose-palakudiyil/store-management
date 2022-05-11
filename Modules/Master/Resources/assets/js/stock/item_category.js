var url_prefix ='item-category';
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
            { className: "text-center", "targets": [ 3] }
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
        
            if($('.multiselect-select-all-filtering').length)
                {
                    
                  // Full featured example
                   $('.multiselect-select-all-filtering').multiselect({
                       includeSelectAllOption: true,
                       enableFiltering: true,
                       enableCaseInsensitiveFiltering: true,
                       templates: {
                           filter: '<li class="multiselect-item multiselect-filter"><i class="icon-search4"></i> <input class="form-control" type="text"></li>'
                       },
                       onSelectAll: function(option, checked) 
                       {

                           var selected = []; 
                           $('.multiselect-full-featured option:selected').each(function()  { selected.push([$(this).val()]);}); 
                           $.uniform.update(); 
                       },
                       onChange: function(option, checked) 
                       {  

                           var selected = []; 
                           $('.multiselect-full-featured option:selected').each(function()  { selected.push([$(this).val()]);}); 

                           if(selected.length <=0)
                           {
                               $('.multiselect-full-featured').multiselect("clearSelection");
                               $.uniform.update();   
                           } 

                           var totalCheckboxes = $('.multiselect-full-featured option').length;
                           if(totalCheckboxes == selected.length)
                           {
                              $('.multiselect-full-featured').multiselect('selectAll', true); 
                              $.uniform.update();  
                           } 
                       } 
                   }); 
               }
               
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
                else if (element.attr("name") == "measurement_id[]" ){  $("#measurement_err").html(error); }
                else{  error.insertAfter(element);}       
            }, 
            rules: {  
                        'name':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                        'measurement_id[]':{required:true, normalizer: function(value) { return $.trim(value);  },maxlength: 255 },
                        'status':{required:true, normalizer: function(value) { return $.trim(value);  } },
//                        'thumb_icon':  { required:true,   fileType: { types: ["jpg", "jpeg", "png", "gif"] }, maxFileSize: { "unit": "MB",  "size": 2  }, }, 
            }
        }); 
//        if($('input[name="HdnEdit"]').length)
//        {
//            $('input[name="thumb_icon"]').rules('remove', 'required');
//        }
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
