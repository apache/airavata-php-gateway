<title>dREG Gateway</title>
<div id="navbar" class="navbar navbar-inverse">
      <div class="container-fluid" style="background:white;">
        <div class="navbar-header" style="background:white;">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand scroll" href="{{ URL::to('/') }}">
            <span class="scigap-logo" ><img src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dREG-logo-muted.png"/></span>
          </a>
        </div>
<style>
.menuactive{
color:blue;
}
</style>
        <div class="collapse navbar-collapse" >
          <ul class="nav navbar-nav navbar-right">
            <li><a class="scroll hidden" href="#home"></a></li>
            <li><a class="scroll" @if( $_SERVER['REQUEST_URI'] === "/" ) style="color:blue" @else style="color:black" @endif href="{{ URL::to('/') }}/">Home</a></li>
            <li><a class="scroll" @if(strpos($_SERVER['REQUEST_URI'], "pages/doc") !== false) style="color:blue" @else style="color:black" @endif href="{{ URL::to('/') }}/pages/doc">dREG Documentation</a></li>
            <li><a class="scroll" @if(strpos($_SERVER['REQUEST_URI'], "pages/dtox-doc") !== false) style="color:blue" @else style="color:black" @endif href="{{ URL::to('/') }}/pages/dtox-doc">dTOX dcumentation</a></li>
            <li><a class="scroll" @if(strpos($_SERVER['REQUEST_URI'], "pages/software") !== false) style="color:blue" @else style="color:black" @endif href="{{ URL::to('/') }}/pages/software">Software/Package</a></li>
            <li><a class="scroll" @if(strpos($_SERVER['REQUEST_URI'], "pages/about") !== false) style="color:blue" @else style="color:black" @endif href="{{ URL::to('/') }}/pages/about">About</a></li>

            <!--
            @if(! Session::has('loggedin'))
            <li><a class="scroll" href="{{ URL::to('/') }}/create">Create Account</a></li>
            <li><a class="scroll" href="{{ URL::to('/') }}/login">Login</a></li>
            @endif
            -->
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>  

    <link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Arvo:400,700' rel='stylesheet' type='text/css'>
    
    
    <!--[if IE 9]>
        <script src="js/PIE_IE9.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
        <script src="js/PIE_IE678.js"></script>
    <![endif]-->

    <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
    <![endif]-->

<link media="all" type="text/css" rel="stylesheet" href="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/css/style.css"/>

