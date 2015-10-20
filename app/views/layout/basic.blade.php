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
var email =  "{{ Session::get("user-profile")["email"] }}";
var fullName = "{{Session::get("user-profile")["firstname"] . " " . Session::get("user-profile")["lastname"]}}"
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

</body>

@show


@section('scripts')
@include('layout/fixed-scripts')
<script type="text/javascript">
	/* keeping a check that footer stays atleast at the bottom of the window.*/
	var bh = $("body").height();
	if( bh < $(window).height()/2){
		$(".theme-footer").css("position", "relative").css("top", $(window).height()/4);
    }
    var bw = $("body").width();
    if( bw > 767){
        $(".hero-unit").height( bw*0.36);
    }
</script>
@show

</html>