@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($LicenceRenewal, ['method' => 'PATCH', 'route' => ['others.licence-renewal.update', $LicenceRenewal->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::others.licence-renewal.form', compact('LicenceRenewal'))
    {!! Form::close() !!} 
@stop
 
             
  