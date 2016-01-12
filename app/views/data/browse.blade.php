@extends('layout.basic')

@section('page-header')
    @parent
@stop

@section('content')
    <br/>
    <div class="container" style="max-width: 60%;">
        @foreach( $metadataModels as $index => $metadataModel )
        <div>
            <div><a href="">{{$metadataModel->userFriendlyName}}</a></div>
            <div>

            </div>
            <div>
                <span><a href="">{{$metadataModel->username}}</a></span> |
                <span>2016-01-05 02:22</span> |
                <span>ID: {{$metadataModel->metadataId}}</span> |
                <span>{{$metadataModel->size/1024}} MB</span>
            </div>
            <div>Dynamo similar to C2-2, with a stress-free boundaries
                and a 100km thick thermal layer
                codBC.txt.in
                2 0 0 0.129 0.0
            </div>
        </div>
        <hr/>
        @endforeach
    </div>
@stop

@section('scripts')
    @parent
@stop