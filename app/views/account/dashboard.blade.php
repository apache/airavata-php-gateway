@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/admin.css')}}
@stop

@section('content')
<div class="container">
    <div class="col-md-12">
        @if( Session::has("message"))
        <div class="row">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                {{ Session::get("message") }}
            </div>
        </div>
        {{ Session::forget("message") }}
        @endif

        @if( Session::has('authorized-user') || Session::has('admin') )
        <div class="row text-center breathing-space">
            <h1>Let's get started!</h1>
        </div>
        <div class="row text-center admin-options">

            <div class="row well">

                <h3>See what's happening in your projects</h3>

                <a href="{{URL::to('/')}}/project/browse">
                    <div class="@if( Session::has('admin')) col-md-4 @else col-md-6 @endif well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-off console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Projects</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/experiments/browse">
                    <div class="@if( Session::has('admin')) col-md-4 @else col-md-6 @endif well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-tasks console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Experiments</h4>
                        </div>
                    </div>
                </a>

                @if( Session::has('admin'))
                <a href="{{URL::to('/')}}/admin/dashboard/experiments">
                    <div class="col-md-4  well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-stats console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Experiment Statistics</h4>
                        </div>
                    </div>
                </a>
                @endif
            </div>

            @if( Session::has('admin'))
            <div class="row well">

                <h3>Manage Users Access</h3>
                <a href="{{URL::to('/')}}/admin/dashboard/users">
                    <div class="col-md-6  well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-user  console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Users</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/admin/dashboard/roles">
                    <div class=" col-md-6  well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-eye-open  console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse User Roles</h4>
                        </div>
                    </div>
                </a>
            </div>

            <div class="row well">

                <h3>Manage Computing and Storage Resouces and Preferences for your Gateway</h3>

                <a href="{{URL::to('/')}}/cr/browse">
                    <div class=" col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-briefcase  console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Compute Resources</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/admin/dashboard/gateway">
                    <div class=" col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-sort console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Gateway Prefereces</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/sr/browse">
                    <div class=" col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-folder-open console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Storage Resources</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/admin/dashboard/credential-store">
                    <div class=" col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-lock console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Credential Store</h4>
                        </div>
                    </div>
                </a>

            </div>

            <div class="row well">

                <h3>Manage Application Modules, Interfaces and Deployments</h3>
                <a href="{{URL::to('/')}}/app/module">
                    <div class="col-md-4 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-th-large console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Application Modules</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/app/interface">
                    <div class="col-md-4 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-phone console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Application Interfaces</h4>
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/app/deployment">
                    <div class="col-md-4 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-random console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            <h4>Browse Application Deployments</h4>
                        </div>
                    </div>
                </a>
                @endif

                
                <!--
                <div class=" col-md-4">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-list-alt console-icon"></span>
                    </div>
                    <div class="col-md-12">
                        Reports
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-question-sign console-icon"></span>
                    </div>
                    <div class="col-md-12">
                        Support
                    </div>
                </div>
            </div>
            -->

        </div>
    </div>
    @else
    <div>
        <div class="row text-center breathing-space">
            <h1>Hi! You look new here.</h1>
        </div>
        <div class="row well">
            <h4>Please contact the gateway administrator to request access to run experiments and more.</h4>
        </div>
    </div>
    @endif
</div>

@stop