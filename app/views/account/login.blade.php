@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="col-md-offset-4 col-md-4">

    @if (!empty($auth_password_option))
        @include('partials/login-form', array("auth_name" => $auth_password_option["name"]))
        @if (!empty($auth_code_options))
            <h3 id="login-option-separator" class="horizontal-rule">OR</h4>
        @endif
    @endif
    @if (!empty($auth_code_options))
        @include('partials/login-external', array("auth_code_options" => $auth_code_options))
    @endif
</div>

@stop