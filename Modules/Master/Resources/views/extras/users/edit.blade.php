@extends('master::layouts.master')   
@section('content')  
    {!! Form::model($auth, ['method' => 'PATCH', 'route' => ['extras.users.update', $auth->id],'class'=>'form-valide','id'=>'_form','enctype'=>'multipart/form-data']) !!}     
    @include('master::extras.users.form', compact('auth'))
    <input type="hidden"  name="HdnEdit" value="{{$auth->id}}" >
    {!! Form::close() !!} 
@stop
 
             
  