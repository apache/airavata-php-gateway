<link media="all" type="text/css" rel="stylesheet" href="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/css/zoom.css"/>

<div class="col-md-offset-2 col-md-8 breathing-space scigap-info" >
	<h1 class="text-center">Welcome to dREG gateway!</h1>
	<p class="text-center" style="color:#cccccc;">
	Find the location of enhancers and promoters using PRO-seq, GRO-seq, and ChRO-seq data.<br/>
	</p>
	<p class="text-center" style="color:#444444;">
The gateway status and updates are <A target=_blank href="https://github.com/Danko-Lab/dREG/blob/master/gateway-update.md"><B>here!</b></A>
</p>
	<hr/>

	<div class="col-md-5"  style="margin-left: 5%">
        <H2> How does it work?</H2>
	<p style="font-size:14px; margin-top:20px; text-align:justify">
The dREG gateway predicts the location of enhancers and promoters using PRO-seq, GRO-seq, or ChRO-seq data.  The server takes as input bigWig files provided by the user, which represent PRO-seq signal on the plus and minus strand.  The gateway uses pre-trained dREG and dREG-HD models to identify divergent transcript start sites and impute the predicted DNase-1 hypersensitivity signal across the genome. The dREG gateway uses pre-trained models that work in any mammalian organism (other organisms coming soon).</p>
<img id="myImg" alt="dREG Gateway" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dREG-works.png" style="width:100%"></img>
<p class="text-center"><small>Click the figure to enlarge it</small></p>

<div id="myModal" class="modal" onkeypress="document.getElementById('myModal').style.display='none'" >
  <span class="close" onclick="document.getElementById('myModal').style.display='none'">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<script>
// Get the modal
var modal = document.getElementById('myModal');
// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById('myImg');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
img.onclick = function(){
    modal.style.display = "block";
    modalImg.src = this.src;
    captionText.innerHTML = this.alt;
}
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}
</script>

	</div>

	<div class="col-md-offset-1 col-md-5" style="margin-left: 5%">
	<H2> How is it used?</H2>
	<p style="font-size:14px; margin-top:20px;text-align:justify">
Registered users need only upload experimental data in the required format and push the start button. Once the job is finished, the user will be notified by e-mail. Results can be downloaded to the user’s local machine, or viewed in the Genome Browser via the handy trackhub link. </p>

<p style="font-size:14px; margin-top:5px;text-align:justify">
<img src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/webdev-bullet-icon.png" style="height:20px"></img>
Use the Danko lab's <b>mapping pipeline</b> (<A target=_blank href="https://github.com/Danko-Lab/proseq2.0">here</A>) to prepare bigWig files from fastq files or <b>convert BAM files</b> of mapped reads to bigWig (<A target=_blank href="https://github.com/Danko-Lab/RunOnBamToBigWig">here</A>).
</p>

<p style="font-size:14px; margin-top:5px;text-align:justify">
<img src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/webdev-bullet-icon.png" style="height:20px"></img>
See our <A href="{{ URL::to('/') }}/pages/faq">FAQ</A>, <A href="{{ URL::to('/') }}/pages/doc">documentation</a> or <A target=_blank href="https://www.dropbox.com/s/jzlamnd0mej0z76/Chu.dREG_protocol.pdf?dl=0">dREG protocol manuscript</A>  for additional questions.
</p>
<img id="myImg2" alt="dREG model" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dREG-model.png" style="width:100%"></img>
<p class="text-center"><small>Click the figure to enlarge it</small></p>
<div id="myModal2" class="modal" onkeypress="document.getElementById('myModal2').style.display='none'" >
  <span class="close" onclick="document.getElementById('myModal2').style.display='none'">&times;</span>
  <img class="modal-content" id="img02">
  <div id="caption"></div>
</div>

<script>
// Get the modal
var modal = document.getElementById('myModal2');
// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById('myImg2');
var modalImg = document.getElementById("img02");
var captionText = document.getElementById("caption");
img.onclick = function(){
    modal.style.display = "block";
    modalImg.src = this.src;
    captionText.innerHTML = this.alt;
}
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}
</script>

	</div>

<br style="clear:both"/>
<hr style="color:green"/>

<h1 class="text-center">Publications</h1>
<div style="width: 92%; margin-left: 5%; padding-left:15px">
<table>
<tr>
<td width="50px"><img id="myImg2" alt="dREG model" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/n1.png" style="width:100%"></img></td>
<td>
<p  class="text-left" style="margin-left: 5px; margin-top:10px;">
Danko, C. G., Hyland, S. L., Core, L. J., Martins, A. L., Waters, C. T., Lee, H. W., ... & Siepel, A. (2015). <A target=_blank href="http://www.nature.com/nmeth/journal/v12/n5/full/nmeth.3329.html">Identification of active transcriptional regulatory elements from GRO-seq data.</A> Nature methods, 12(5), 433-438.</p>
</td>
</tr>

<tr>
<td width="50px"><img id="myImg2" alt="dREG model" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/n2.png" style="width:100%"></img></td>
<td>
<p  class="text-left" style="margin-left: 5px; margin-top:10px;">
Wang, Z., Chu, T., Choate, L. A., & Danko, C. G. (2018). <A target=_blank href="https://www.biorxiv.org/content/early/2018/05/14/321539.abstract">Identification of regulatory elements from nascent transcription using dREG.</A> bioRxiv, 321539.
</p>
</td>
</tr>


</table>
</div>

<br style="clear:both"/>
<hr style="color:green"/>

</div>

<div class="col-md-12 text-center" style=" padding: 20px 0 20px 0; background-color:#FFFFFF">
	<a href="http://airavata.apache.org/" target="_blank">
		<img width="200px" src="{{URL::to('/')}}/themes/{{Session::get('theme')}}/assets/img/poweredby-airavata-logo.png">
	</a>
	<a href="http://www.nsf.gov/" target="_blank" class="logo-seperation">
		<img width="200px" src="{{URL::to('/')}}/themes/{{Session::get('theme')}}/assets/img/nsf-logo.png">
	</a>
	<a href="https://www.xsede.org/" target="_blank" class="logo-seperation">
		<img width="200px" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/powered-by-xsede.gif">
	</a>
</div>

