<style>
@media (min-width: 768px) { 
.logo-seperation{
	margin-left:100px;
}
}
</style>


<div class="col-md-12 text-center" style="background:#2c3e50; padding: 20px 0 20px 0;">

	<a href="http://www.dankolab.org/" target="_blank">
		<img width="200px" height="69px" style="margin-top:5px; margin-bottom:5px" src="{{URL::to('/')}}/themes/{{Session::get('theme')}}/assets/img/dankolab-logo.png">
	</a>
	<a href="http://www.vet.cornell.edu/baker/index.cfm" target="_blank" class="logo-seperation">
        <img width="200px"  style="margin-top:5px; margin-bottom:5px" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/baker-logo.png"/>
	</a>
	<a href="http://www.cornell.edu/" target="_blank" class="logo-seperation">
		<img width="200px"  style="margin-top:5px; margin-bottom:5px"  src="{{URL::to('/')}}/themes/{{Session::get('theme')}}/assets/img/cornell-logo.png">
	</a>
</div>

<div id="footer">
</div>
<script type="text/javascript">
	if( bw > 767){
        $(".hero-unit").height( bw*0.50);
    }
</script>
<div id="copyright">
	<div class="container">
		<p class="center" style="text-align:center">
        © 2017 <a target=_blank href="http://www.dankolab.org/">Danko Lab</A>,  <a target=_blank href="http://www.vet.cornell.edu/baker/index.cfm">The Baker Institute for Animal Health</A>, <a target=_blank href="http://www.vet.cornell.edu">College of Veterinary Medicine</A>, <a target=_blank  href="http://www.cornell.edu/">Cornell University</A>, Ithaca, New York 14853-6401<br>
        Please report problems with this page to the <a href="mailto:zw355@cornell.edu" style="text-decoration: underline">webmaster</a>.
		</p>
		<p class="right" style="text-align:center">
		follow us on:&nbsp;&nbsp;&nbsp;
			<a href="https://twitter.com/charlesdanko"> <img src="{{URL::to('/')}}/themes/{{Session::get('theme')}}/assets/img/Twitter26x21.png" width="26" height="26"></a>
			<a href="https://github.com/danko-lab" > <svg aria-hidden="true" style="margin-bottom:-5px" class="octicon octicon-mark-github" height="26" version="1.1" viewBox="0 0 16 16" width="26"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg></a>
			
		</p>
		<br class="clear">
	</div>
</div>
		
