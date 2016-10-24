@extends('layout.basic')

@section('page-header')
@parent
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
    <h1>SSH Keys</h1>
    <h3>Default SSH Key</h3>
    <form class="form-inline" action="{{ URL::to('/') }}/account/set-default-credential" method="post">
        <div class="form-group">
            <label for="defaultToken" class="sr-only">Select default SSH key</label>
            <select class="form-control" id="defaultToken" name="defaultToken">
                @foreach ($credentialSummaries as $credentialSummary)
                <option
                @if ($credentialSummary["credentialStoreToken"] == $defaultCredentialSummary["credentialStoreToken"])
                selected
                @endif
                value="{{ $credentialSummary["credentialStoreToken"] }}">{{ $credentialSummary["description"] }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-default">Update default</button>
    </form>

    <h3>SSH Key Info</h3>
    <table class="table table-bordered table-condensed" style="word-wrap: break-word; table-layout: fixed; width: 100%;">
        <thead>
            <tr>
                <th>Description</th>
                <th>Public Key</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($credentialSummaries as $credentialSummary)
            <tr>
                <td>
                    {{ $credentialSummary["description"] }}
                </td>
                <td>
                    {{ $credentialSummary["publicKey"] }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@stop

@section('scripts')
@parent
<script></script>
@stop