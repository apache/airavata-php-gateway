<div class="container">       
				
                
<h1 class="text-center top-space">Software/Package</h1>
<div class="post-entry">

          <p class="description">The <B>dREG gateway</B> is web service built on the Apache Airavata software framework and the XSEDE platform using the following software packages:</p>

          <p class="description">[1] <B>dREG package</B>: <A href="https://github.com/Danko-Lab/dREG">https://github.com/Danko-Lab/dREG</A>.</p>
          <p class="description">
The dREG package is developed to detect the divergently oriented RNA polymerase in GRO-seq, PRO-seq, or ChRO-seq data using support vector machines (e1070 or Rgtsvm package).</p>

          <p class="description">[2] <B>dREG.HD package</B>: <A href="https://github.com/Danko-Lab/dREG.HD">https://github.com/Danko-Lab/dREG.HD</A>.</p>
          <p class="description">The dREG.HD pa/ckage refines the location of TREs obtained using dREG by imputing DNAse-I hypersensitivity.</p>



          <p class="description">[3] <B>dTOX package</B>: <A href="https://github.com/Danko-Lab/dTOX">https://github.com/Danko-Lab/dTOX</A>.</p>
          <p class="description">The dTOX package detects transcription factor binding in PRO-seq, DNase-I-seq, and ATAC-seq using support vector machines and random forests. </p>


          <p class="description">[4] <B>Rgtsvm package</B>: <A href="https://github.com/Danko-Lab/Rgtsvm">https://github.com/Danko-Lab/Rgtsvm</A>.</p>
          <p class="description">
Rgtsvm implements support vector classification and support vector regression on a GPU to accelerate the computational speed of training and predicting large-scale models. </p>

          <p class="description">[5] <B>rtfbsdb package</B>: <A href="https://github.com/Danko-Lab/rtfbs_db">https://github.com/Danko-Lab/rtfbs_db</A>.</p>
          <p class="description">
Rtfbsdb implements TFBS scaning acorss whole genome and TF enrichment test with the aid of CIS-BP, Jolma and other TF databases.  
          </p>

          <p class="description">[6] <B>tfTarget package</B>: <A href="https://github.com/Danko-Lab/tfTarget">https://github.com/Danko-Lab/tfTarget</A>.</p>
          <p class="description">
Identify transcription factor-enhancer/promoter-gene network from run-on sequencing data. 
         </p>

          <p class="description">[7] <B>Proseq 2.0</B>: <A href="https://github.com/Danko-Lab/proseq2.0">https://github.com/Danko-Lab/proseq2.0</A>.</p>
          <p class="description">
Preprocesses and Aligns Run-On Sequencing (PRO/GRO/ChRO-seq) data from Single-Read or Paired-End Illumina Sequencing.
         </p>

         <p class="description">[8] <B>Airavata PHP Gateway</B>: <A href="https://github.com/apache/airavata-php-gateway.git">https://github.com/apache/airavata-php-gateway.git</A>.</p>
         <p class="description">
Airavata PHP Gateway provides an API to build web sites which interact with high performance computers that are part of XSEDE.
         </p>

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
