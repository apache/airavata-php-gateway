@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')
<div class="container">
{{var_dump($userResourceProfile)}}
</div>

@stop

@section('scripts')
@parent
<script></script>
@stop