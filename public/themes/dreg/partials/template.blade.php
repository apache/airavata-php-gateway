<link media="all" type="text/css" rel="stylesheet" href="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/css/zoom.css"/>

<div class="col-md-offset-2 col-md-8 breathing-space scigap-info" >
        <h1 class="text-center">Welcome to dREG and dTOX gateway!</h1>
        <p class="text-center" style="color:#cccccc;">
        Find the location of transcriptional regulatory elements and transcription factoring binding using genomic data.<br/>
        </p>
        <p class="text-center" style="color:#444444;">
        The gateway status and updates are <A target=_blank href="https://github.com/Danko-Lab/dREG/blob/master/gateway-update.md"><B>here!</b></A>
        </p>
        <hr/>

<div class="col-md-offset-1 col-md-5" style="margin-left: 5%" >
    <H2> How is dREG used?</H2>
    <p style="font-size:14px; margin-top:10px; text-align:justify">
    The dREG model in the gateway predicts the location of enhancers and promoters using PRO-seq, GRO-seq, or ChRO-seq data.  The server takes as input bigWig files provided by the user, which represent PRO-seq signal on the plus and minus strand.  The gateway uses a pre-trained dREG model to identify divergent transcript start sites and impute the predicted DNase-I hypersensitivity signal across the genome. The current dREG model works in any mammalian organism.</p>
    <p style="font-size:14px; margin-top:10px;text-align:justify">
Registered users need only upload experimental data in the required format and push the start button. Once the job is finished, the user will be notified by e-mail. Results can be downloaded to the user’s local machine, or viewed in the Genome Browser via the handy trackhub link. </p>

    <p style="font-size:14px; margin-top:5px;text-align:justify">
    <img src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/webdev-bullet-icon.png" style="height:20px"></img>
    Use the Danko lab's <b>mapping pipeline</b> (<A target=_blank href="https://github.com/Danko-Lab/proseq2.0">here</A>) to prepare bigWig files from fastq files or <b>convert BAM files</b> of mapped reads to bigWig (<A target=_blank href="https://github.com/Danko-Lab/RunOnBamToBigWig">here</A>).
    </p>

    <p style="font-size:14px; margin-top:5px;text-align:justify">
    <img src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/webdev-bullet-icon.png" style="height:20px"></img>
    See our <A href="{{ URL::to('/') }}/pages/doc#faq">FAQ</A>, <A href="{{ URL::to('/') }}/pages/doc">documentation</a> or <A target=_blank href="https://www.dropbox.com/s/jzlamnd0mej0z76/Chu.dREG_protocol.pdf?dl=0">dREG protocol manuscript</A>  for additional questions.</p>

   <img id="myImg2" alt="dREG model" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dREG-model.png" style="width:100%"></img>
   <p class="text-center"><small>Click the figure to enlarge it</small></p>
   <div id="myModal2" class="modal" onkeypress="document.getElementById('myModal2').style.display='none'" >
     <span class="close" onclick="document.getElementById('myModal2').style.display='none'">&times;</span>
     <img class="modal-content" id="img02">
     <div id="caption"></div>
   </div>
</div>


<div class="col-md-offset-1 col-md-5" style="margin-left: 5%">
    <H2> How is dTOX used? </H2>
    <p style="font-size:14px; margin-top:10px;text-align:justify"> 
The dTOX models in the gateway predict the binding status of transcription factor binding sites using PRO-seq, ATAC-seq, or DNase-I-seq data. The server takes as input bigWig files provided by the user, which represent the PRO-seq, ATAC-seq, or DNase-1-seq signal on the plus and minus strand. The gateway uses two pre-trained dTOX models to identify transcription factor binding patterns genome-wide. The current dTOX models work in any mammalian organism and on any motif that has an associated position-weight matrix. To run the dTOX models on genomes other than hg19 and mm10, download the R package (<A target=_blank href="https://github.com/Danko-Lab/dTOX">here</A>). </p>    
    <p style="font-size:14px; margin-top:10px;text-align:justify">
The web operations are same as the dREG model. Users need to login -> upload data -> run data. Results can be downloaded or viewed in the WashU Genome browser.</p>
    <p style="font-size:14px; margin-top:5px;text-align:justify">
    <img src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/webdev-bullet-icon.png" style="height:20px"></img>
    Use the Danko lab's pipeline to <b>convert BAM files</b> of mapped reads to bigWig (<A ta
rget=_blank href="https://github.com/Danko-Lab/RunOnBamToBigWig">here for PRO-seq</A>), (<A ta
rget=_blank href="https://github.com/Danko-Lab/utils/dnase/BamToBigWig">here for DNase-I-seq</A>), and (<A ta
rget=_blank href="https://github.com/Danko-Lab/utils/atacseq/BamToBigWig">here for ATAC-seq</A>).
    </p>

    <p style="font-size:14px; margin-top:5px;text-align:justify">
   <img src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/webdev-bullet-icon.png" style="height:20px"></img>
   See our <A href="{{ URL::to('/') }}/pages/dtox-doc#faq">FAQ</A>, <A href="{{ URL::to('/') }}/pages/dtox-doc">documentation</a> for additional questions.
   </p>
   
   <img id="myImg3" alt="dREG model" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dTOX-model.png" style="width:100%"></img>
   <p class="text-center"><small>Click the figure to enlarge it</small></p>
   <div id="myModal3" class="modal" onkeypress="document.getElementById('myModal3').style.display='none'" >
   <span class="close" onclick="document.getElementById('myModal3').style.display='none'">&times;</span>
   <img class="modal-content" id="img03">
     <div id="caption"></div>
   </div>
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

<script>
// Get the modal
var modal = document.getElementById('myModal3');
// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById('myImg3');
var modalImg = document.getElementById("img03");
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


<br style="clear:both"/>
<hr style="color:green"/>

<h1 class="text-center">Gateway Introduction</h1>

<div style="width: 92%; margin-left: 5%; padding-left:15px">
<p>
The dREG gateway is a cloud platform developed by the <A target=_blank href="http://www.dankolab.org/">Danko lab</a> at the <A target=_blank href="https://www2.vet.cornell.edu/departments-centers-and-institutes/baker-institute">Baker Institute</A>, <A target=_blank href="http://www.cornell.edu">Cornell University</A> and supported by the <A target=_blank href="https://www.scigap.org/">SciGap</A> (Science Gateway Platform as a Service) and <A target=_blank href="https://www.xsede.org/">XSEDE</A> (Extreme Science and Engineering Discovery Environment).</p> 
Currently, this gateway hosts two bioinformatics services for functional analysis of sequencing data, dREG transcriptional regulatory element peak calling and dTOX transcription factor binding prediction. Both services take as input files (either PRO-seq, GRO-seq, ATAC-seq or DNase-1-seq) provided by the user, and then uses pre-trained models to conduct prediction and post-processing on GPU computing nodes. The architecture and details are <A href="{{ URL::to('/') }}/pages/about">here</a>.
</div>

<hr style="color:green"/>

<h1 class="text-center">Publications</h1>
<div style="width: 92%; margin-left: 5%; padding-left:15px">
<table>

<tr>
<td width="50px"><img id="myImg2" alt="dREG model" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/n1.png" style="width:100%"></img></td>
<td>
<p  class="text-left" style="margin-left: 5px; margin-top:10px;">
Wang, Z., Chu, T., Choate, L. A., & Danko, C. G. (2019). <A target=_blank href="https://genome.cshlp.org/content/29/2/293.short">Identification of regulatory elements from nascent transcription using dREG.</A> Genome research, 29(2), 293-303.
</p>
</td>
</tr>

<tr>
<td width="50px"><img id="myImg2" alt="dREG model" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/n2.png" style="width:100%"></img></td>
<td>
<p  class="text-left" style="margin-left: 5px; margin-top:10px;">
Danko, C. G., Hyland, S. L., Core, L. J., Martins, A. L., Waters, C. T., Lee, H. W., ... & Siepel, A. (2015). <A target=_blank href="http://www.nature.com/nmeth/journal/v12/n5/full/nmeth.3329.html">Identification of active transcriptional regulatory elements from GRO-seq data.</A> Nature methods, 12(5), 433-438.</p>
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

