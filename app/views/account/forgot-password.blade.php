@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="col-md-offset-3 col-md-6">

    <h3> Did you forget the password to your account? </h3>
    <h4> Please enter your username, you registered with.</h4>
    <form role="form" method="POST" action="{{ URL::to('/') }}/forgot-password">
        <div class="form-group form-horizontal">
            <img src="{{$imageUrl}}"/>
            <div>
                <input name="imagePath" type="hidden" value="{{$imagePath}}" class="form-control"/>
                <input name="secretKey" type="hidden" value="{{$secretKey}}" class="form-control"/>
                <br/>
                <input class="form-control" id="userAnswer" name="userAnswer" placeholder="captcha text" required="required"/>
                <br/>
                <input name="username" type="username" value="" class="form-control" placeholder="username" required/>
            </div>
        </div>
        <div class="form-group btn-toolbar">
            <div class="btn-group">
                <input type="submit" class="form-control btn btn-primary" value="Submit"/>
            </div>
        </div>
    </form>
    @stop