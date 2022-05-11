@extends('master::layouts.master') 
@section('content')

    <div class="panel panel-flat">
        <div class="panel-body">
            <form method="get">
                <div class="row"> 
                    <div class="col-md-3">
                       <div class="form-group">
                           <label>UID: <span class="text-danger">*</span></label>
                           <input  type="text" class="form-control datepicker-menus" id="uid" name="uid"  placeholder="Enter uid" value ="<?php if($request->exists('uid')) : echo $request->uid; endif; ?>" >
                            <div>
                               @if($errors->has('uid'))
                                   <div class="validation-error-label" style="display: inline-block;">{{ $errors->first('uid') }}</div>
                               @endif
                           </div>
                       </div>
                    </div> 
                    <div class="col-md-2 " style="padding-top: 25px">
                        <button type="submit" class="btn btn-primary"  >GET</button> 
                    </div>
                </div>
            </form>
            <?php   if( $request->exists('uid')  &&   $batch_item==null): echo '<code>Sorry no data found</code>';
            elseif($request->exists('uid')  &&   $batch_item!=null): 
                $item = Modules\Master\Entities\Items::find($batch_item->item_id);
            ?>
            <table>
                <tr>
                    <td width='120px'> <b>Item Name</b> </td>
                    <td> {{isset($item->name) ? $item->name : ''}} <hr/> </td>
                </tr>
                <tr>
                    <td width='120px'> <b>Item ID</b> </td>
                    <td> {{isset($item->id) ? $item->id : ''}} <hr/> </td>
                </tr>
                <tr>
                    <td width='120px'><b> UID </b></td>
                    <td> {{$batch_item->unique_id}}  <hr/></td>
                </tr>
                <tr>
                    <td width='120px'><b> Actions </b></td>
                    <td> 
                        <table>
                            <tr>
                                <td style="padding:  10px">
                                    <button data-type='purchase-entry' data-item_id='{{$item->id}}' data-uid='{{$batch_item->unique_id}}' type="submit" class="btn btn-success action"  >Purchase Entry</button> 
                                </td>
                                <td style="padding: 10px"> 
                                        <button type="submit" data-type='maintenance' data-item_id='{{$item->id}}' data-uid='{{$batch_item->unique_id}}'  class="btn btn-primary action"  >Maintenance</button> 
                                </td>
                                <td style="padding: 10px">
                                    <button type="submit" data-type='breakdown' data-item_id='{{$item->id}}' data-uid='{{$batch_item->unique_id}}' class="btn btn-danger action"  >Breakdown</button> 
                                </td>
                                <td style="padding: 10px">
                                    <button type="submit" data-type='licence-renewal' data-item_id='{{$item->id}}' data-uid='{{$batch_item->unique_id}}' class="btn btn-warning action"  >Licence-renewal</button> 
                                </td>
                                <td style="padding: 10px">
                                    <button type="submit" data-type='breakage' data-item_id='{{$item->id}}' data-uid='{{$batch_item->unique_id}}' class="btn btn-info action"  >Breakage</button> 
                                </td>
                                <td style="padding: 10px">
                                    <button type="submit" data-type='calibration' data-item_id='{{$item->id}}' data-uid='{{$batch_item->unique_id}}' class="btn btn-default action"  >Calibration</button> 
                                </td>
                            </tr>
                        </table> <hr/>
                    </td>
                </tr>
            </table> 
            <?php    
            endif; ?>
        </div>
    </div>
<div id="ActionsDiv" style="margin: 0;padding: 0"> </div> 
@stop

@section('js')
 
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script> 
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    <script src="{{asset('Modules/Master/Resources/assets/js/barcode/barcode.js')}}"></script> 
@stop