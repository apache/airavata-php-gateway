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

        <div class="row text-center">
            <h1>Let's get started!</h1>
        </div>
        <div class="row text-center admin-options">

            <div class="row">
                <a href="{{URL::to('/')}}/admin/dashboard/experiments">
                    <div class="col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-off console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            Experiments
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/admin/dashboard/users">
                    <div class=" col-md-offset-1 col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-user  console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            Users
                        </div>
                    </div>
                </a>

                <a href="{{URL::to('/')}}/admin/dashboard/roles">
                    <div class=" col-md-offset-1 col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-eye-open  console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            Roles
                        </div>
                    </div>
                </a>

            </div>

            <div class="row">

                <a href="{{URL::to('/')}}/cr/browse">
                    <div class="col-md-3 well">
                        <div class="col-md-12">
                            <span class="glyphicon glyphicon-briefcase  console-icon"></span>
                        </div>
                        <div class="col-md-12">
                            Resources
                        </div>
                    </div>
                </a>

                <div class="col-md-offset-1 col-md-3 well">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-tasks  console-icon"></span>
                    </div>
                    <div class="col-md-12">
                        Application Catalog
                    </div>
                    <select onchange="location = this.options[this.selectedIndex].value;">
                        <option>-- Select --</option>
                        <option value="{{URL::to('/')}}/app/interface">Interface</option>
                        <option value="{{URL::to('/')}}/app/module">Module</option>
                        <option value="{{URL::to('/')}}/app/deployment">Deployment</option>
                    </select>
                </div>

                <div class=" col-md-offset-1 col-md-3 well">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-sort console-icon"></span>
                    </div>
                    <div class="col-md-12">
                        Gateways
                    </div>
                    <select onchange="location = this.options[this.selectedIndex].value;">
                        <option>-- Select --</option>
                        <option value="{{URL::to('/')}}/gp/create">Create</option>
                        <option value="{{URL::to('/')}}/gp/browse">Browse</option>
                    </select>
                </div>
                <!--
                <div class=" col-md-offset-1 col-md-3 well">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-list-alt console-icon"></span>
                    </div>
                    <div class="col-md-12">
                        Reports
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 well">
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
</div>

@stop