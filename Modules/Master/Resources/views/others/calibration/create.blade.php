@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($Calibration, ['method' => 'POST', 'route' => ['others.calibration.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data','autocomplete'=>'off']) !!}    
    @include('master::others.calibration.form',compact($Calibration)) 
    {!! Form::close() !!} 
@stop
 
             
  