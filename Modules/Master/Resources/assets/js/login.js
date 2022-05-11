$(function() 
{
     $('.form-input-styled').uniform();
        /* ------------------------------------------------------------------------- */ 
        /* -------------------------- form validate -------------------------------- */ 
        /* ------------------------------------------------------------------------- */
        $("#master_login").validate({ 
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        errorClass: 'validation-error-label',
        successClass: 'validation-valid-label',
        validClass: 'validation-valid-label',
        highlight: function(element, errorClass) { $(element).removeClass(errorClass); },
        unhighlight: function(element, errorClass) { $(element).removeClass(errorClass); }, 
        // Different components require proper error label placement
        
        errorPlacement: function(error, element) {  
                if (element.attr("name") == "username" ){  $("#username_error").html(error); } 
                else if (element.attr("name") == "password" ){  $("#password_error").html(error); } 
                else { error.insertAfter(element); }   
            }, 
            
          rules: { 
                    'username':{required:true, normalizer: function(value) { return $.trim(value);  } },
                    'password':{required:true, normalizer: function(value) { return $.trim(value);  } }
                 } 
        });
});