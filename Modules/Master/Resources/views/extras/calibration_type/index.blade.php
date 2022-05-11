@extends('master::layouts.master') 

@section('css')  

@stop

@section('content') 
<!-- Basic datatable -->
<div class="panel panel-flat"> 
    <table class="table datatable-basic" id="datatable" data-url='{{route('extras.calibration_type_list')}}'>
        <thead>
            <tr>
                <th>No. </th> 
                <th>Name </th>  
                <th>Days</th>
                <th>Status</th>
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
    var editBtn     = <?php echo (isset($editBtn) && $editBtn) ? $editBtn : 'false' ?>;
    var deleteBtn   = <?php echo (isset($deleteBtn) && $deleteBtn) ? $deleteBtn : 'false' ?>;
</script>

  <script src="{{asset('public/global_assets/js/plugins/notifications/noty.min.js')}}"></script>
  <script src="{{asset('public//global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
  <script src="{{asset('Modules/Master/Resources/assets/js/extras/calibration_type.js')}}"></script>    
@stop
