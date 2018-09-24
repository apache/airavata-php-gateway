<div class="container">       
				
                
<h1 class="text-center top-space">FAQ</h1>
<div class="post-entry">
<p>dREG Gateway is online service that supports Web-based science through the execution of online computational experiments and the management of data. The items below are trying to  answer qustions from the users</p>

<p><b>Q: How should I prepare bigWig files for use with the dREG gateway?</b></p>
<p>A: Information about how to prepare files can be found  <A href="https://github.com/Danko-Lab/proseq2.0"> here </A>.</p>

<p><b>Q: How should I do when I meet the computational failure in the dREG gateway?</b></p>
<p>A: There are two types of error you may have, we explain how to identify your error and how to handle it <A href="https://dreg.dnasequence.org/pages/doc#failure"> here</A>.</p>

<p><b>Q: Which browser works well with the dREG gateway?</b></p>
<p>A: We have tested in the Firefox, Google Chrome and Safari so far. For IE (version 10 or 11) and some version of Safari, you maybe have trouble showing sequence data in WashU genome browser. For Safari users, please read next Q&A.</p>


<p><b>Q: What should the Safari users be aware of?</b></p>   
<p>A: By default, Safari unzips a zip file automatically when you download it. However dREG results are compressed by the 'bgzip' command which is not compatiable with the Safari method. It would be probelmatic when you download dREG results. Please refer to <A href="https://octet.oberlin.edu/does-your-mac-unzip-zip-files-automatically/"> this link </A> to disable this feature in Safari and then download the compressed results from dREG gateway. </br>
Secondly, when you click the genome browser link, please use the Left-Click, don't use Right-Click menu and the menu option "open a new tab".   
</p>

<p><b>Q: What types of enhancers and promoters can be identified using the dREG gateway?</b></p>
<p>A: As a general rule of thumb, high-quality datasets provide very similar groups of enhancers and promoters as ChIP-seq for H3K27ac.  This suggests that dREG identifies the location of all of the so-called 'active' class of enhancers and promoters.  </p>

<p><b>Q: Will the dREG gateway work with my data type?</b></p>
<p>A: The dREG gateway will work well with data collected by any run-on and sequencing method, including GRO-seq, PRO-seq, or ChRO-seq.  Other methods that map the location of RNA polymerase genome wide using alternative tools (for example, NET-seq) will most likely work well, but are not officially supported.</p>

<p><b>Q: Will the pre-trained models work using data from my species?</b></p>
<p>A: Models are currently available only in mammalian organisms.  The length and density of genes, which vary considerably between highly divergent species, affects the way that a transcribed promoter or enhancer looks.  For this reason, models can only be used in species.  We are working to create models in widely-used model organisms, including drosophila and C. elegans. </p> 

<p><b>Q: How deeply do I need to sequence PRO-seq libraries?</b></p>
<p>A: Sensitivity is reasonable at ~40 million mapped reads and saturates at ~100 million mapped reads.  See our analysis here: <A href="http://www.nature.com/nmeth/journal/v12/n5/fig_tab/nmeth.3329_SF3.html">supplementary figure 3 in dREG paper</A>.</p>

<p><b>Q: How long do my data and results keep in the dREG gateway?</b></p>
<p>A: One month.</p>

<p><b>Q: How to I cite the dREG gateway?</b></p>
<p>A: Please cite one of our papers if you use dREG results in your publication:<BR/>
<A target="_blank" href="http://www.nature.com/nmeth/journal/v12/n5/full/nmeth.3329.html">
(1) Danko, C. G., Hyland, S. L., Core, L. J., Martins, A. L., Waters, C. T., Lee, H. W., ... & Siepel, A. (2015). Identification of active transcriptional regulatory elements from GRO-seq data. Nature methods, 12(5), 433-438. </A></p> 
<A target="_blank" href="https://www.biorxiv.org/content/early/2018/05/14/321539.abstract">
(2) Wang, Z., Chu, T., Choate, L. A., & Danko, C. G. (2018). Identification of regulatory elements from nascent transcription using dREG. bioRxiv, 321539. </A></P>


<p><b>Q: Do I have to create account before using this service?</b></p>
<p>A: Yes, this system is supported by an NSF funded supercomputing resource known as <A title="XSEDE" href="http://www.xsede.org">XSEDE</A>, who regularly needs to report bulk usage statistics to NSF.  Nevertheless, data that you provide are completely safe.</p>

<p><b>Q: How do I know the status of the computational nodes? </b></p>
<p>A: Since we can't update this web site very often, the gateway status is updated <A target=_blank href="https://github.com/Danko-Lab/dREG/blob/master/gateway-update.md">here</A> on the dREG page based on the notifications of the XSEDE community. </p>

<p><b>Q: Who do I thank for the computing power? </b></p>
<p>A: This web-based tool is powered by <A title="SciGaP" href="http://www.scigap.org">SciGaP</A> and <A title="Apache Airavata"  href="http://airavata.apache.org/">Apache Airavata</A> and the GPU servers are supported by the <A title="XSEDE" href="http://www.xsede.org">XSEDE</A>.</p>

<p><b>Q: I have another question that is not on this FAQ.  How can I contact you?</b></p>
<p>A: Yes, please contact us with any questions! Zhong(zw355 at cornell.edu).  Charles(cgd24 at cornell.edu).</p>



</div>
<!-- end of .post-entry -->

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
