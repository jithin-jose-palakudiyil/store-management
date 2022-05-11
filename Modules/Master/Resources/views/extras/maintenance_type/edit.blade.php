@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($MaintenanceType, ['method' => 'PATCH', 'route' => ['extras.maintenance-type.update', $MaintenanceType->id],'class'=>'form-valide','id'=>'maintenance_type_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::extras.maintenance_type.form', compact('MaintenanceType'))
    {!! Form::close() !!} 
@stop
 
             
  