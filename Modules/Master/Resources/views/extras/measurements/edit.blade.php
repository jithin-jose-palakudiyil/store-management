@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($Measurement, ['method' => 'PATCH', 'route' => ['extras.measurements.update', $Measurement->id],'class'=>'form-valide','id'=>'measurements_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::extras.measurements.form', compact('Measurement'))
    {!! Form::close() !!} 
@stop
 
             
  