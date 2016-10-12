@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')
<div class="container">
    <h1>SSH Keys</h1>
    <h2>Default SSH Key</h2>

    <table class="table table-bordered table-condensed" style="word-wrap: break-word; table-layout: fixed; width: 100%;">
        <thead>
            <tr>
                <th>Token</th>
                <th>Public Key</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ $token }}
                </td>
                <td>
                    {{ $publicKey }}
                </td>
            </tr>
        </tbody>
    </table>
</div>

@stop

@section('scripts')
@parent
<script></script>
@stop