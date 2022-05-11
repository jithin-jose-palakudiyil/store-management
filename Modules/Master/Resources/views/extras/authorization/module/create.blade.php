@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($module, ['method' => 'POST', 'route' => ['extras.module.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::extras.authorization.module.form',compact($module)) 
    {!! Form::close() !!} 
@stop
 
             
  