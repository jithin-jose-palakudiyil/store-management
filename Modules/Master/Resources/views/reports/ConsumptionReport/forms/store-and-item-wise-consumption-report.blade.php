@extends('master::layouts.master')

@section('content')<div class="panel panel-flat">
    <div class="panel-body">
       
        <form method="POST" action="{{route('consumption_reports_download',$slug)}}"   enctype="multipart/form-data">
            <div class="row">
                {{ csrf_field() }} 
                  <div class="col-md-4">
                   <div class="form-group">
                       <label>Store: <span class="text-danger">*</span></label>
                       <select name="store" id="store" data-placeholder="select store" class="select " data-minimum-results-for-search="-1">
                           <option></option> 
                           <?php
                           $store = \Modules\Master\Entities\Store::where('status',1)->get();
                           foreach ($store as $key => $value) :
                               ?>
                           <option value="{{$value->id}}">{{$value->name}}</option>     
                               <?php
                           endforeach;
                           ?>
                       </select>
                       <div id="store_err">
                           @if($errors->has('store'))
                               <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('store') }}</div>
                           @endif
                       </div>
                   </div>
                </div>   
                <div class="col-md-4">
                   <div class="form-group">
                       <label>Item: <span class="text-danger">*</span></label>
                       <input  type="text"  class="form-control autocomplete" id="item" name="item"  placeholder="Enter item" value="" >
                       <div id="">
                           @if($errors->has('item'))
                               <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('item') }}</div>
                           @endif
                       </div>
                   </div>
                </div>   
                <div class="col-md-2">
                   <div class="form-group">
                       <label>Item ID: <span class="text-danger">*</span></label>
                        <input type="text" readonly="" class="form-control" id="item_id"  name="item_id" value="" >
                    
                       <div id="">
                           @if($errors->has('item_id'))
                               <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('item_id') }}</div>
                           @endif
                       </div>
                   </div>
                </div>  
                <div class="col-md-2 " style="padding-top: 25px">
                    <button type="submit" class="btn btn-primary"  >Download</button> 
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
<style>
/*     input[type="text"]:disabled {
  background: #ef3636;color: #fff;
}*/
     .ui-autocomplete {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: none;
  float: left;
  min-width: 160px;
  padding: 5px 0;
  margin: 2px 0 0;
  list-style: none;
  font-size: 14px;
  text-align: left;
  background-color: #ffffff;
  border: 1px solid #cccccc;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 4px;
  -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  background-clip: padding-box;
}

.ui-autocomplete > li > div {
  display: block;
  padding: 3px 20px;
  clear: both;
  font-weight: normal;
  line-height: 1.42857143;
  color: #333333;
  white-space: nowrap;
}

.ui-state-hover,
.ui-state-active,
.ui-state-focus {
  text-decoration: none;
  color: #262626;
  background-color: #f5f5f5;
  cursor: pointer;
}

.ui-helper-hidden-accessible {
  border: 0;
  clip: rect(0 0 0 0);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute;
  width: 1px;
}

 </style>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>
    <script>
        $(function() 
        {
                // Simple select without search
          $('.select').select2({ minimumResultsForSearch: Infinity});

          // Styled checkboxes and radios
          $('.styled').uniform();
          
          
          
          
          
        $(".autocomplete").autocomplete({
            source: function (request, response) { 
                //get data item number
                
              
              
                  $("#item_id").val('');
                    $.ajax({
                        url:base_url+'/'+master_prefix+'/item-autocomplete', 
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
                                   return { label: obj.name, value: obj.id  , } ;
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
                    $("#item_id").val('');
                     $("#item").val('');
                    
                    return false;
                } else { 
                    $("#item_id").val(ui.item.value);
                    $("#item").val(ui.item.label);
                     
                    return false;
                }
            },response: function(event, ui) {}
        }).data("ui-autocomplete")._renderItem = function (ul, item) {return $("<li></li>").data("item.autocomplete", item).append("<a>" + item.label + "</a>").appendTo(ul);};
    
          
          
          
          
          
          
          
          
          
          
          
          

        });
    </script>
@stop