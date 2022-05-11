@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($auth, ['method' => 'POST', 'route' => ['extras.users.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::extras.users.form',compact($auth)) 
    {!! Form::close() !!} 
@stop
 
             
  