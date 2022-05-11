@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($LicenceRenewal, ['method' => 'POST', 'route' => ['others.licence-renewal.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data','autocomplete'=>'off']) !!}    
    @include('master::others.licence-renewal.form',compact($LicenceRenewal)) 
    {!! Form::close() !!} 
@stop
 
             
  