@extends('master::layouts.master') 

@section('css')  

@stop

@section('content') 
<!-- Basic datatable -->
    <div class="panel panel-flat"> 
        <table class="table datatable-basic" id="datatable" data-url='{{route('others.calibration_list')}}'>
            <thead>
                <tr> 
                    <th>Item Name </th>  
                    <th>Calibration Type</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>                                              
<!-- Basic datatable -->    
    <div id="modal_update"> </div>
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
    var updateBtn   = <?php echo (isset($updateBtn) && $updateBtn) ? $updateBtn : 'false' ?>;
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script> 
    <script src="{{asset('public/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{asset('public/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>  
    
    
  <script src="{{asset('public/global_assets/js/plugins/notifications/noty.min.js')}}"></script>
  <script src="{{asset('public//global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
  <script src="{{asset('Modules/Master/Resources/assets/js/others/calibration.js')}}"></script>    
@stop
