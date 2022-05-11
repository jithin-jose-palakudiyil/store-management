@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($Breakage, ['method' => 'PATCH', 'route' => ['breakage-m.update', $Breakage->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::breakage.m.form', compact('Breakage'))
    {!! Form::close() !!} 
@stop
 
             
  