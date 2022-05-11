@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($Supplier, ['method' => 'POST', 'route' => ['extras.suppliers.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::extras.suppliers.form',compact($Supplier)) 
    {!! Form::close() !!} 
@stop
 
             
  