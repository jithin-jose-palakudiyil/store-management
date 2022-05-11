@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($Measurement, ['method' => 'POST', 'route' => ['extras.measurements.store'],'class'=>'form-valide','id'=>'measurements_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::extras.measurements.form',compact($Measurement)) 
    {!! Form::close() !!} 
@stop
 
             
  