@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($Store, ['method' => 'POST', 'route' => ['stock.store.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::stock.store.form',compact($Store)) 
    {!! Form::close() !!} 
@stop
 
             
  