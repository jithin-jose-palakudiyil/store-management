@extends('master::layouts.master')  
@section('content')  
    {!! Form::model($GatePass, ['method' => 'POST', 'route' => ['gate-pass.store','breakage='.$breakage->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}    
    @include('master::gate-pass.form',compact($GatePass)) 
    {!! Form::close() !!} 
@stop
 
             
  