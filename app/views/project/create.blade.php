@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/sharing.css') }}
@stop

@section('content')

<div class="container" style="max-width: 750px">

    <h1>Create a new project</h1>


    <form action="create" method="post" role="form" class="project-creation-form">
        <div class="form-group required">
            <label for="project-name" class="control-label">Project Name</label>
            <input type="text" class="form-control projectName" name="project-name" id="project-name"
                   placeholder="Enter project name" autofocus required maxlength="50">
        </div>

        <div class="form-group">
            <label for="project-description">Project Description</label>
            <textarea class="form-control" name="project-description" id="project-description"
                      placeholder="Optional: Enter a short description of the project" maxlength="200"></textarea>
        </div>

        <div class="form-group">
            <label for="project-share">Sharing Settings</label><br />
            <button class="btn btn-default" name="project-share" id="project-share">Share With Other Users</button><br />
            <label>Show</label>
            <div id="show-results-group" class="btn-group" role="group" aria-label="Show Groups or Users">
                <button type="button" class="show-groups show-results-btn btn btn-primary">Groups</button>
                <button type="button" class="show-users show-results-btn btn btn-default">Users</button>
            </div>
            <label>Order By</label>
            <select class="order-results-selector">
                <option value="username">Username</option>
                <option value="firstlast">First, Last Name</option>
                <option value="lastfirst">Last, First Name</option>
                <option value="email">Email</option>
            </select>
            <div id="shared-users" class="text-align-center">
                <p>This project has not been shared</p>
            </div>
            <input id="share-settings" name="share-settings" type="hidden" value="" />
        </div>

        <input name="save" type="submit" class="btn btn-primary create-project" value="Save">
        <input name="clear" type="reset" class="btn btn-default" value="Clear">

    </form>

</div>

{{ HTML::image("assets/Profile_avatar_placeholder_large.png", 'placeholder image', array('class' => 'baseimage')) }}

@stop

@section('scripts')
@parent
<script>
    $(".projectName").blur(function () {
        $(this).val($.trim($(this).val()));
    });
    var users = {{ $users }};
</script>
{{ HTML::script('js/sharing/sharing_utils.js') }}
{{ HTML::script('js/sharing/share.js') }}
@stop
