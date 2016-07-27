@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/sharing.css') }}
@stop

@section('content')

<?php
//$echoResources = array('localhost', 'trestles.sdsc.edu', 'lonestar.tacc.utexas.edu');
//$wrfResources = array('trestles.sdsc.edu');

//$appResources = array('Echo' => $echoResources, 'WRF' => $wrfResources);
?>


<div class="container">

    <div class="col-md-offset-3 col-md-6">
        <h1>Edit Experiment</h1>

        <form action="{{URL::to('/')}}/experiment/edit" method="POST" role="form" enctype="multipart/form-data">
            <input type="hidden" name="expId" value="<?php echo Input::get('expId'); ?>"/>

            @include('partials/experiment-inputs', array( "expInputs", $expInputs))

            @if( count( $expInputs['computeResources']) > 0)
            <div class="btn-toolbar">
                <div class="btn-group">
                    <input name="save" type="submit" class="btn btn-primary"
                           value="Save" <?php if (!$expInputs['expVal']['editable']) echo 'disabled' ?>>
                    <input name="launch" type="submit" class="btn btn-success"
                           value="Save and launch" <?php if (!$expInputs['expVal']['editable']) echo 'disabled' ?>>
                </div>
            </div>
            @else
            <p class="well alert alert-danger">
                This experiment is connected with an Application which is currently not deployed on any Resource. The experiment cannot be launched at the moment.
            </p>
            @endif
        </form>
    </div>

</div>

{{ HTML::image("assets/Profile_avatar_placeholder_large.png", 'placeholder image', array('class' => 'baseimage')) }}

@include('partials/sharing-form-modal')
@stop


@section('scripts')
@parent
<script>
    var users = {{ $users }};
</script>
{{ HTML::script('js/sharing/sharing_utils.js') }}
{{ HTML::script('js/sharing/share.js') }}
<script>
    $('.file-input').bind('change', function () {

        var inputFileSize = Math.round(this.files[0].size / (1024 * 1024));
        if (inputFileSize > $("#allowedFileSize").val()) {
            alert("The input file size is greater than the allowed file size (" + $("#allowedFileSize").val() + " MB) in a form. Please upload another file.");
            $(this).val("");
        }

    });

    $("#enableEmail").change(function () {
        if (this.checked) {
            $("#emailAddresses").attr("required", "required");
            $(this).parent().children(".emailSection").removeClass("hide");
        }
        else {
            $(this).parent().children(".emailSection").addClass("hide");
            $("#emailAddresses").removeAttr("required");
        }

    });

    $(".addEmail").click(function () {
        var emailInput = $(this).parent().find("#emailAddresses").clone();
        emailInput.removeAttr("id").removeAttr("required").val("").appendTo(".emailAddresses");
    });

    $("#compute-resource").change(function () {
        var crId = $(this).val();
        $(".loading-img ").removeClass("hide");
        $.ajax({
            url: '../experiment/getQueueView',
            type: 'get',
            data: {crId: crId},
            success: function (data) {
                $(".queue-view").html(data);
                $(".loading-img ").addClass("hide");
            }
        });
    });
</script>
@stop
