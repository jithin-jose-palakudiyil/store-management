@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($Item, ['method' => 'POST', 'route' => ['stock.items.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::stock.items.form',compact($Item)) 
    {!! Form::close() !!} 
@stop
 
             
  