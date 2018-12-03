@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/sharing.css') }}
{{ HTML::style('css/tusupload.css')}}
@stop

@section('content')
<div class="col-md-offset-3 col-md-6">
{{-- Commented by dREG
    <h1>Create a new experiment</h1>
--}}
    <h1>Create a new dREG or dTOX experiment</h1>

    <!-- <form action="{{URL::to('/')}}/experiment/create" method="POST" role="form" enctype="multipart/form-data"> -->
    <form action="{{URL::to('/')}}/experiment/create" method="POST" role="form" >

        <input type="hidden" name="experiment-name" value="{{{$expInputs['experimentName']}}}">
        <input type="hidden" name="experiment-description" value="{{{$expInputs['experimentDescription']}}}">
        <input type="hidden" name="project" value="{{{$expInputs['project']}}}">
        <input type="hidden" name="application" value="{{{$expInputs['application']}}}">

        @include('partials/experiment-inputs', array("expInputs" => $expInputs, "queueDefaults" =>
        $expInputs['queueDefaults']) )

        <div class="form-group btn-toolbar">
            <div class="btn-group">
                <button onclick="disableWarn()" name="save" type="submit" class="btn btn-primary" value="Save">Save</button>
                <button onclick="disableWarn()" name="launch" type="submit" class="btn btn-success" id="expLaunch" value="Save and launch">Save
                    and launch
                </button>
            </div>

            <a onclick="disableWarn()" href="{{URL::to('/')}}/experiment/create" class="btn btn-default" role="button">Start over</a>
        </div>

    </form>

<input type="hidden" id="allowedFileSize" value="{{ $expInputs['allowedFileSize'] }}"/>
</div>

{{ HTML::image("assets/Profile_avatar_placeholder_large.png", 'placeholder image', array('class' => 'baseimage')) }}

@include('partials/sharing-form-modal', array("entityName" => "experiment"))

@stop

@section('scripts')
@parent
<script>
    var users = {{ $users }};
    var owner = {{ $owner }};
    var projectOwner = {{ $projectOwner }};
    $('#entity-share').data({url: "{{URL::to('/')}}/project/unshared-users", resourceId: {{json_encode($expInputs['project'])}}})
</script>
{{ HTML::script('js/sharing/sharing_utils.js') }}
{{ HTML::script('js/sharing/share.js') }}
{{ HTML::script('js/util.js') }}
{{ HTML::script('js/tus.js') }}
<script>
    var warn = true;

    function disableWarn(){
        warn = false;
        return false;
    }

    $('.file-input').bind('change', function () {

        var allowedFileSize = $("#allowedFileSize").val();
        var tooLargeFilenames = util.validateMaxUploadFileSize(this.files, allowedFileSize);

//        if (tooLargeFilenames.length > 0) {
//            var singleOrMultiple = tooLargeFilenames.length === 1 ? " the file [" : " each of the files [";
//            alert("The size of " + singleOrMultiple + tooLargeFilenames.join(", ") + "] is greater than the allowed file size (" + allowedFileSize + " MB) in a form. Please upload another file.");
//            $(this).val("");
//        }
//Adding for dREG gateway 
//Big file resumable upload       
        var file = this.files[0];

        progressdiv = $(this).parent().parent().children(".progress")[0]; 
        progressdiv.style.display ="block";
        progressbar = $(progressdiv).children("#progressBar")[0]; 
        progressbar.style.width = "0%";
        urlnode = $(this).parent().children(".urlpath")[0]; 
        if( $(urlnode).val() != "")
        {
           var prevfile = $(urlnode).val();
           var previtem = prevfile.split(":");
           var prevurl = previtem[1]+":"+previtem[2];
 
           $.ajax({
               url: prevurl,
               type: 'DELETE',
               success: function(result) {
                 // Do something with the result
               }
           });
        }
 
        // Create a new tus upload
        var upload = new tus.Upload(file, {
             endpoint: '{{URL::to('/')}}/experiment/upload',
             retryDelays: [0, 1000, 3000, 5000],
             resume: false, 
	     progressbar:progressbar,
	     progressdiv:progressdiv,
 	     urlnode:urlnode,   
             filename:file.name,
             onError: function(error) {
                  if (error.originalRequest) {
                       if (confirm("Failed because: " + error + "\nDo you want to retry?")) 
                          {
                              options.resume = false;
                              options.uploadUrl = upload.url;
                              upload = new tus.Upload(file, options);
                              upload.start();
                              return;
                          }
                  } else {
                       alert("Failed because: " + error);
                       this.progressdiv.style.display="none";
                  }

   		  reset();
             },
             onProgress: function(bytesUploaded, bytesTotal) {
                 var percentage = (bytesUploaded / bytesTotal * 100).toFixed(2)
                 this.progressbar.style.width = percentage + "%";
                 console.log(bytesUploaded, bytesTotal, percentage + "%");
             },
             onSuccess: function() {
                 console.log("Upload %s to %s", upload.file.name, upload.url)
                 this.progressdiv.style.display ="none";
                 this.urlnode.value = this.filename + ":" + upload.url;
             }
         });
         
         // Start the upload
         upload.start();

//End of dREG gateway 
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
        $(".queue-view ").addClass("hide");
        $.ajax({
            url: '../experiment/getQueueView',
            type: 'get',
            data: {crId: crId},
            success: function (data) {
                $(".queue-view").html(data);
                $(".queue-view ").removeClass("hide");
                $(".loading-img ").addClass("hide");
            }
        });
    });

    window.onbeforeunload = function() {
        if(warn){
            return "Are you sure you want to navigate to other page ? (you will loose all unsaved data)";
        }
        warn = true;
    }

    //Selecting the first option as the default
    $( document ).ready(function() {
        var $cr = $("#compute-resource");
        var crId = $cr.val();
        if ($cr.children("option").size() === 1 && crId !== "") {
            $(".loading-img ").removeClass("hide");
            $.ajax({
                url: '../experiment/getQueueView',
                type: 'get',
                data: {crId: crId},
                success: function (data) {
                    $(".queue-view").html(data);
                    $(".loading-img ").addClass("hide");
                },error : function(data){
                    $(".loading-img ").addClass("hide");
                }
            });
        }
    });

    //Setting the file input view JS code
    $( document ).ready(function() {
        function readBlob(opt_startByte, opt_stopByte, fileId) {

            var files = document.getElementById(fileId).files;
            if (!files.length) {
                alert('Please select a file!');
                return;
            }

            var file = files[0];
            var start = 0;
            var stop = Math.min(512*1024,file.size - 1);

            var reader = new FileReader();

            // If we use onloadend, we need to check the readyState.
            reader.onloadend = function(evt) {
                if (evt.target.readyState == FileReader.DONE) { // DONE == 2
                    $('#byte_content').html(evt.target.result.replace(/(?:\r\n|\r|\n)/g, '<br />'));
                    $('#byte_range').html(
                            ['Read bytes: ', start + 1, ' - ', stop + 1,
                                ' of ', file.size, ' byte file'].join(''));
                }
            };

            var blob = file.slice(start, stop + 1);
            reader.readAsBinaryString(blob);

            $('#input-file-view').modal('show');
        }

        $( ".readBytesButtons" ).click(function() {
            var startByte = $(this).data('startbyte');
            var endByte = $(this).data('endbyte');
            var fileId = $(this).data('file-id');
            readBlob(startByte, endByte, fileId);
        });
    });

    updateList = function() {
        var input = document.getElementById('optInputFiles');
        var output = document.getElementById('optFileList');

        output.innerHTML = '<ul>';
        for (var i = 0; i < input.files.length; ++i) {
            output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
        }
        output.innerHTML += '</ul>';
    }
</script>
@stop
