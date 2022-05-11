@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($Store, ['method' => 'PATCH', 'route' => ['stock.store.update', $Store->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::stock.store.form', compact('Store'))
    {!! Form::close() !!} 
@stop
 
             
  