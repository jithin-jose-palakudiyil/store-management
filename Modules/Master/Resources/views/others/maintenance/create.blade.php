@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($Maintenance, ['method' => 'POST', 'route' => ['others.maintenance.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data','autocomplete'=>'off']) !!}    
    @include('master::others.maintenance.form',compact($Maintenance)) 
    {!! Form::close() !!} 
@stop
 
             
  