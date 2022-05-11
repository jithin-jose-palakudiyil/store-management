@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($CalibrationType, ['method' => 'POST', 'route' => ['extras.calibration-type.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::extras.calibration_type.form',compact($CalibrationType)) 
    {!! Form::close() !!} 
@stop
 
             
  