@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($Item, ['method' => 'PATCH', 'route' => ['stock.items.update', $Item->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::stock.items.form', compact('Item'))
    {!! Form::close() !!} 
@stop
 
             
  