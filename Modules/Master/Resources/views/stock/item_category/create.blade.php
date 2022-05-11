@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($ItemCategory, ['method' => 'POST', 'route' => ['stock.item-category.store'],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::stock.item_category.form',compact($ItemCategory)) 
    {!! Form::close() !!} 
@stop
 
             
  