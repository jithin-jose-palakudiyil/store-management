@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($Indent, ['method' => 'POST', 'route' => ['stock.indents.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::stock.indents.form',compact($Indent)) 
    {!! Form::close() !!} 
@stop
 
             
  