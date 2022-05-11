@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($Maintenance, ['method' => 'PATCH', 'route' => ['others.maintenance.update', $Maintenance->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::others.maintenance.form', compact('Maintenance'))
    {!! Form::close() !!} 
@stop
 
             
  