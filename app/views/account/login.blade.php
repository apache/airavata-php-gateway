@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="col-md-offset-4 col-md-4">

    @if (!empty($auth_password_option))
        @include('partials/login-form', array("auth_name" => $auth_password_option["name"]))
        @if (!empty($auth_code_options))
            <h4>OR</h4>
        @endif
    @endif
    @foreach ($auth_code_options as $auth_code_option)
        <a href="{{ $auth_code_option["auth_url"] }}" class="btn btn-primary">Sign in with {{{ $auth_code_option["name"] }}}</a>
    @endforeach
</div>

@stop