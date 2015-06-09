@extends('layout.basic')

@section('page-header')
    @parent
    {{ HTML::style('css/style.css') }}
@stop

@section('content')

<div class="container">
	<div class="col-md-offset-2 col-md-8">
		<h3>Add Resource Data</h3>
		<form role="form" method="POST" action="{{ URL::to('/') }}/cr/create">

		
		</form>
	</div>
</div>

@stop

@section('scripts')
	@parent
    {{ HTML::script('js/script.js') }}

	<script>
		$(document).ready( function(){

			
		});
	</script>
@stop