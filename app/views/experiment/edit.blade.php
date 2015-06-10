@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<?php
//$echoResources = array('localhost', 'trestles.sdsc.edu', 'lonestar.tacc.utexas.edu');
//$wrfResources = array('trestles.sdsc.edu');

//$appResources = array('Echo' => $echoResources, 'WRF' => $wrfResources);
?>


<div class="container">

    <div class="col-md-offset-3 col-md-6">
        <h1>Edit Cloned Experiment</h1>

        <form action="{{URL::to('/')}}/experiment/edit" method="POST" role="form" enctype="multipart/form-data">
            <input type="hidden" name="expId" value="<?php echo Input::get('expId'); ?>"/>

            @include('partials/experiment-inputs')


            <div class="btn-toolbar">
                <div class="btn-group">
                    <input name="save" type="submit" class="btn btn-primary"
                           value="Save" <?php if (!$expInputs['expVal']['editable']) echo 'disabled' ?>>
                    <input name="launch" type="submit" class="btn btn-success"
                           value="Save and launch" <?php if (!$expInputs['expVal']['editable']) echo 'disabled' ?>>
                </div>
            </div>


        </form>
    </div>

</div>

@stop


@section('scripts')
@parent
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
</script>
@stop