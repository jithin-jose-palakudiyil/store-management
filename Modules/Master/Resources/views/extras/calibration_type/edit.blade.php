@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($CalibrationType, ['method' => 'PATCH', 'route' => ['extras.calibration-type.update', $CalibrationType->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::extras.calibration_type.form', compact('CalibrationType'))
    {!! Form::close() !!} 
@stop
 
             
  