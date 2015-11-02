<?php 
$theme = Theme::uses(Session::get("theme"));
$title = Session::get("portal-title");
?>

@section ('page-header')
@include("layout/fixed-header", array(
                            "title" => $title
                        ))
@show
<style>
/*z index of sidebar is 100.*/
.theme-header{
    position: relative;
    z-index:101;
}
</style>
<div class="theme-header">
<!-- Header from theme -->
@if( isset($theme) )
{{ $theme->partial("header") }}
@endif
</div>

<body>

<!-- Getting user info -->
@if(Session::has("user-profile"))
<script>
var email =  "{{ Session::get("user-profile")["email"] }}";
var fullName = "{{Session::get("user-profile")["firstname"] . " " . Session::get("user-profile")["lastname"]}}"
</script>
@endif

<div class="pga-header">
{{ CommonUtilities::create_nav_bar() }}
</div>

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
	margin-top: 20px;
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
	var bh = $("html").height();
	if( bh < $(window).height()){
//		$(".theme-footer").css("position", "relative").css("top", $(window).height()/4);
    }
    var bw = $("body").width();
    if( bw > 767){
        $(".hero-unit").height( bw*0.36);
    }

    //put sidebar below all headers in admin dashboards
    if( $(".side-nav").length > 0){
        var headerHeight = $(".pga-header").height() + $(".theme-header").height();
        $(".side-nav").css("padding-top", headerHeight);

        var selectedDashboardHeight = $(window).height() - headerHeight;
        if( selectedDashboardHeight < $(".side-nav").height())
        {
            $(".side-nav").height( selectedDashboardHeight).css("overflow-y", "scroll").css("overflow-x", "none");
        }
    }

    $(".floating").click( function(){
        $('html,body').animate({
            scrollTop: $(".seagrid-info").offset().top},
        'slow');
        $(".seagrid-info").scrollTop( $(window).scrollTop() + 150);
    })
</script>
@show

</html>