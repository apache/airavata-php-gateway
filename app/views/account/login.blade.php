@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="col-md-offset-4 col-md-4">

    <h3>
        Login
        <small>
            <small> (Not registered? <a href="create">Create account</a>)</small>
        </small>
    </h3>


    <form action="login" method="post" role="form">
        @if( Session::has("invalid-credentials") )
        {{ CommonUtilities::print_error_message('Invalid username or password. Please try again.') }}
        @endif
        <?php
        Session::forget("invalid-credentials");
        ?>

        <div class="form-group">
            <label class="sr-only" for="username">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username" autofocus required>
        </div>
        <div class="form-group">
            <label class="sr-only" for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        <input name="Submit" type="submit" class="btn btn-primary btn-block" value="Sign in">
    </form>

    <small>
        <small> (Forgot Password? Click <a href="{{URL::to('/') }}/forgot-password">here</a>)</small>
    </small>
</div>

@stop