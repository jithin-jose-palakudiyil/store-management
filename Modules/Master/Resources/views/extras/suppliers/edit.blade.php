@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($Supplier, ['method' => 'PATCH', 'route' => ['extras.suppliers.update', $Supplier->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::extras.suppliers.form', compact('Supplier'))
    {!! Form::close() !!} 
@stop
 
             
  