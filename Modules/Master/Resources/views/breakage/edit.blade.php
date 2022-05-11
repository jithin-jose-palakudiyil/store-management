@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($Breakage, ['method' => 'PATCH', 'route' => ['breakage.update', $Breakage->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::breakage.form', compact('Breakage'))
    {!! Form::close() !!} 
@stop
 
             
  