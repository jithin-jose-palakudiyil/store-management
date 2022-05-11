@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($PurchaseEntry, ['method' => 'POST', 'route' => ['stock.purchase-entry.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::stock.purchase-entry.form',compact($PurchaseEntry)) 
    {!! Form::close() !!} 
    <!-- item modal -->
    <div id="modal_item"></div>
    <!-- /item modal -->
@stop
 
             
  