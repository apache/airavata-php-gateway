@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="col-md-offset-3 col-md-6">

    <h3> Did you forget the password to your account? </h3>
    <h4> Please enter your email id, you registered with.</h4>

    <div class="form-group form-horizontal">
        <div class="col-md-8"><input type="email" value="" class="form-control" placeholder="email"/></div>
        <div class="col-md-2"><input type="submit" class="form-control btn btn-primary" value="Submit"/></div>
    </div>

    @stop