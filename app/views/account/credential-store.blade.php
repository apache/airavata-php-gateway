@extends('layout.basic')

@section('page-header')
@parent
<style>
.credential-item {
    padding-right: 220px;
    position: relative;
}
.credential-item .credential-buttons {
    height: 34px;
    width: 220px;
    position: absolute;
    top: 50%;
    margin-top: -17px;
    right: 0px;
}
</style>
@stop

@section('content')
<div class="container">
    @if( Session::has("message"))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        {{{ Session::get("message") }}}
    </div>
    {{ Session::forget("message") }}
    @endif

    @if( Session::has("error-message"))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        {{{ Session::get("error-message") }}}
    </div>
    {{ Session::forget("error-message") }}
    @endif

    <h1>SSH Keys</h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Default SSH Key</h3>
        </div>
        <div class="panel-body">
            <form class="form-inline" action="{{ URL::to('/') }}/account/set-default-credential" method="post">
                <div class="form-group">
                    <label for="defaultToken" class="sr-only">Select default SSH key</label>
                    <select class="form-control" id="defaultToken" name="defaultToken">
                        @foreach ($credentialSummaries as $credentialSummary)
                        <option
                        @if ($credentialSummary->token == $defaultCredentialSummary->token)
                        selected
                        @endif
                        value="{{ $credentialSummary->token }}">{{ $credentialSummary->description }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-default">Update default</button>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Add SSH Key</h3>
        </div>
        <div class="panel-body">
            @if ($errors->has())
            @foreach ($errors->all() as $error)
            {{ CommonUtilities::print_error_message($error) }}
            @endforeach
            @endif
            <form id="add-credential" class="form-inline" action="{{ URL::to('/') }}/account/add-credential" method="post">
                <div id="credential-description-form-group" class="form-group">
                    <label for="credential-description" class="sr-only">Description for new SSH key</label>
                    <input type="text" id="credential-description" name="credential-description"
                    class="form-control" placeholder="Description" required/>
                </div>
                <button type="submit" class="btn btn-default">Add</button>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">SSH Keys</div>
      <ul class="list-group">
        @foreach ($credentialSummaries as $credentialSummary)
        <li class="list-group-item credential-item">
            {{ $credentialSummary->description }}
            <div class="credential-buttons">
                <button type="button" class="btn btn-default copy-credential"
                    data-clipboard-text="{{$credentialSummary->publicKey}}"
                    data-toggle="tooltip" data-placement="bottom" data-title="Copied!" data-trigger="manual">Copy to clipboard</button>
                @if ($credentialSummary->canDelete)
                <button data-token="{{$credentialSummary->token}}"
                    data-description="{{$credentialSummary->description}}"
                    class="btn btn-default delete-credential">Delete</button>
                @endif
            </div>
        </li>
        @endforeach
      </ul>
    </div>
</div>

<div class="modal fade" id="delete-credential-modal" tabindex="-1" role="dialog" aria-labelledby="delete-credential-modal-title"
     aria-hidden="true">
    <div class="modal-dialog">

        <form action="{{URL::to('/')}}/account/delete-credential" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center" id="delete-credential-modal-title">Delete SSH Public Key</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="credentialStoreToken"/>

                    Do you really want to delete the <span class="credential-description"></span> SSH public key?
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="submit" class="btn btn-danger" value="Delete"/>
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel"/>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@stop

@section('scripts')
@parent
{{ HTML::script('js/clipboard.min.js') }}
<script>
$('.delete-credential').on('click', function(){

    var credentialStoreToken = $(this).data('token');
    var credentialDescription = $(this).data('description');

    $("#delete-credential-modal input[name=credentialStoreToken]").val(credentialStoreToken);
    $("#delete-credential-modal .credential-description").text(credentialDescription);
    $("#delete-credential-modal").modal("show");
});

$('#credential-description').on('invalid', function(event){
    this.setCustomValidity("Please provide a description");
    $('#credential-description-form-group').addClass('has-error');
});
$('#credential-description').on('keyup input change', function(event){
    if (this.checkValidity) {
        // Reset custom error message. If it isn't empty string it is considered invalid.
        this.setCustomValidity("");
        // checkValidity will cause invalid event to be dispatched. See invalid
        // event handler above which will set the custom error message.
        var valid = this.checkValidity();
        $('#credential-description-form-group').toggleClass('has-error', !valid);
    }
});

var clipboard = new Clipboard('.copy-credential');
clipboard.on('success', function(e){
    // Show 'Copied!' tooltip for 2 seconds on successful copy
    $(e.trigger).tooltip('show');
    setTimeout(function(){
        $(e.trigger).tooltip('hide');
    }, 2000);
});
</script>
@stop