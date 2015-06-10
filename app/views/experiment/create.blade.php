@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')
<div class="col-md-offset-3 col-md-6">

    <h1>Create a new experiment</h1>

    <form action="{{URL::to('/')}}/experiment/create" method="POST" role="form" enctype="multipart/form-data">

        <?php

        $disabled = '';
        $experimentName = '';
        $experimentDescription = '';
        $project = '';
        $application = '';

        $echo = '';
        $wrf = '';
        ?>

        <div class="form-group required">
            <label for="experiment-name" class="control-label">Experiment Name</label>
            <input type="text" class="form-control" name="experiment-name" id="experiment-name"
                   placeholder="Enter experiment name" autofocus required="required">
        </div>
        <div class="form-group">
            <label for="experiment-description">Experiment Description</label>
            <textarea class="form-control" name="experiment-description" id="experiment-description"
                      placeholder="Optional: Enter a short description of the experiment"></textarea>
        </div>
        <div class="form-group required">
            <label for="project" class="control-label">Project</label>


            {{ ProjectUtilities::create_project_select($project, !$disabled) }}

        </div>
        <div class="form-group">
            <label for="application">Application</label>

            {{ ExperimentUtilities::create_application_select($application, !$disabled) }}

        </div>
        <div class="btn-toolbar">
            <input name="continue" type="submit" class="btn btn-primary" value="Continue">
            <input name="clear" type="reset" class="btn btn-default" value="Reset values">
        </div>
    </form>

</div>

@stop