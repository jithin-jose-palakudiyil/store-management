@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($module, ['method' => 'PATCH', 'route' => ['extras.module.update', $module->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::extras.authorization.module.form', compact('module'))
    {!! Form::close() !!} 
@stop
 
             
  