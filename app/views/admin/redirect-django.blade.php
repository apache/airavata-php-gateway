@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/admin.css')}}
@stop
<META HTTP-EQUIV="refresh" CONTENT="10;URL={{ $djangoURL }}">
@section('content')
<div class="container">
  <p style="color:red;font-size:300%"> ATTENTION!!! </p>
  <p style="color:black;font-size:150%;font-weight:bold">
  We are moving to the new Django portal. For admin related activies, please
  go to the new Django portal <a href={{ $djangoURL }} >{{ $djangoURL}}. </a>
  This page will automatically redirect to the new Django portal in 10 seconds.</p>
  <p id="demo" style="color:black;font-weight:bold;font-size:200%;text-align:center"></p>
</div>

@stop

@section('scripts')
@parent
<script>
// Set the date we're counting down to
num = 10;
// Update the count down every 1 second
var x = setInterval(function() {

  num = num-1;
  // Output the result in an element with id="demo"
  document.getElementById("demo").innerHTML = num;

  // If the count down is over, write some text
  if (num < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);
</script>
@stop
