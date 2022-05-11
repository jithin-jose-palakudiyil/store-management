@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($permission, ['method' => 'POST', 'route' => ['extras.permissions.store'],'class'=>'form-valide','id'=>'permission_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::extras.authorization.permissions.form',compact($permission)) 
    {!! Form::close() !!} 
@stop
 
             
  