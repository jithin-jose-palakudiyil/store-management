@extends('master::layouts.master') 

@section('css')  

@stop

@section('content') 
 
 
<!-- Basic datatable -->
 

<div class="row">  
 
    <div class="col-md-12 ">
        <?php if(\Auth::guard(master_guard)->user()->role !='master'): ?>
        <a href="{{route('stock.indents').'?type=request_sent'}}">  <button type="submit" class="btn  <?php if($type=='request_sent'): echo 'btn-primary'; else: echo 'btn-defult';  endif; ?> ">Request Sent</button> </a>
        <?php endif; ?>
        <a href="{{route('stock.indents').'?type=request_recived'}}"> <button type="submit" class="btn <?php if($type=='request_recived'): echo 'btn-primary'; else: echo 'btn-defult';  endif; ?> ">Request Recived</button> </a> 
    </div>
</div>
<div class="panel panel-flat"> 
    <table class="table datatable-basic" id="datatable" data-type="{{$type}}" data-url='{{route('stock.indents_list')}}'>
        <thead>
            <tr>
                <th>Indent No. </th> 
                <th>Authority Status </th>   
                <th>Store Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
                                                    
<!-- Basic datatable -->                          
@stop

@section('js')  
<style>
    .btn-light {
    color: #000 !important;
    margin-right: 10px !important;
}
</style>

<script>
    var if_master =<?php  echo (\Auth::guard(master_guard)->user()->role =='master') ? 'true' : 'false'  ?>;
    var editBtn     = <?php echo (isset($editBtn) && $editBtn) ? $editBtn : 'false' ?>;
    var deleteBtn   = <?php echo (isset($deleteBtn) && $deleteBtn) ? $deleteBtn : 'false' ?>;
    var transferBtn   = <?php echo (isset($transferBtn) && $transferBtn) ? $transferBtn : 'false' ?>;
</script>
  <script src="{{asset('public/global_assets/js/plugins/notifications/noty.min.js')}}"></script>
  <script src="{{asset('public//global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
  <script src="{{asset('Modules/Master/Resources/assets/js/stock/indents.js')}}"></script>    
@stop
