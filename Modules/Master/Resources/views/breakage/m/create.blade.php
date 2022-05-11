@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($Breakage, ['method' => 'POST', 'route' => ['breakage-m.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::breakage.m.form',compact($Breakage)) 
    {!! Form::close() !!} 
@stop
 
             
  