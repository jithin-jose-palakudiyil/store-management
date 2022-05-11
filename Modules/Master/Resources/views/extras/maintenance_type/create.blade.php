@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($MaintenanceType, ['method' => 'POST', 'route' => ['extras.maintenance-type.store'],'class'=>'form-valide','id'=>'maintenance_type_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::extras.maintenance_type.form',compact($MaintenanceType)) 
    {!! Form::close() !!} 
@stop
 
             
  