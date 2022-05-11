@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($Calibration, ['method' => 'PATCH', 'route' => ['others.calibration.update', $Calibration->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::others.calibration.form', compact('Calibration'))
    {!! Form::close() !!} 
@stop
 
             
  