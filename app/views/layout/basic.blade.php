<?php 
$theme = Theme::uses(Session::get("theme"));
?>

@section ('page-header')

@include("layout/fixed-header", array(
                            "title" => "PHP Reference Gateway"
                        ))

@show

<!-- Header from theme -->
@if( isset($theme) )
{{ $theme->partial("header") }}
@endif


<body>

<!-- Getting user info -->
@if(Session::has("user-profile"))
<script>
var email = {{ Session::get("user-profile")["email"] }} . "'\n";
var fullName = {{ Session::get("user-profile")["firstname"] . " " . Session::get("user-profile")["lastname"] . "'" }}
</script>
@endif

{{ CommonUtilities::create_nav_bar() }}


<!-- Handling error on pages --> 
<!--  Alerts if guests users try to go to the link without signing in. -->
@if (Session::has("login-alert")) 
    {{ CommonUtilities::print_error_message("You need to login to use this service.") }}
    {{ Session::forget("login-alert") }}
@endif
<!-- if signed in user is not an admin. -->
@if (Session::has("admin-alert"))
    {{ CommonUtilities::print_error_message("You need to be an admin to use this service.") }}
    {{ Session::forget("admin-alert") }}
@endif

<!--  PGA UI lies here. Do not touch. -->
@yield('content')


@include('layout/fixed-footer')

</body>

@show


@section('scripts')
@include('layout/fixed-scripts')
@show

<style>
.theme-footer{
	margin-top: 5%;
}
</style>
@if( isset( $theme))
<footer class="theme-footer">
{{ $theme->partial("footer") }}
</footer>
@endif


</html>