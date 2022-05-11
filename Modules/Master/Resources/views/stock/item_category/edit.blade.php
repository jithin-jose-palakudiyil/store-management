@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($ItemCategory, ['method' => 'PATCH', 'route' => ['stock.item-category.update', $ItemCategory->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::stock.item_category.form', compact('ItemCategory'))
    {!! Form::close() !!} 
@stop
 
             
  