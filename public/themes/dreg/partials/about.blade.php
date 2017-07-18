<div class="container">       
<div>
  <h1 class="text-center top-space">About dREG gateway</h1>
  <p dir="ltr">
  The <A title="dREG gateway" href="http://dreg.dnasequence.org">dREG gateway</A> 
is a cloud service that predicts the location of enhancers and promoters using PRO-seq, GRO-seq, or ChRO-seq data. The hosted service is developed by the Danko lab at the Baker Institute and Cornell University and supported by the <A title="SciGaP" href="http://www.scigap.org">SciGap</A> (Science Gateway Platform as a Service) and  <A  title="XSEDE" href="http://www.xsede.org">XSEDE</A> (Extreme Science and Engineering Discovery Environment).
  </p>
  <p dir="ltr"><A title="SciGaP" href="http://www.scigap.org">SciGaP</A> supports access to core infrastructure services required by Science Gateways, including: user identity, accounts, authorization, and access to multiple computational resources from XSEDE. </p> 
  <p dir="ltr"><A title="XSEDE" href="http://www.xsede.org">XSEDE</A> provides GPU resources under the project (TG-BIO160048: dREG Science Gateway).</p>

  <h1 class="text-center top-space">About Danko Lab</h1>
  <p dir="ltr"><A title="Danko Lab" href="http://www.dankolab.org">Danko Lab</A> at the Cornell University studies how gene expression programs are encoded in mammalian DNA sequences, and how these ‘regulatory codes’ contribute to evolution, development, and disease. Our specialty is developing statistics and machine-learning approaches to analyze genomic sequencing data, prepared using DNase-I-seq, ATAC-seq, PRO-seq, RNA-seq, and related assays. Our tools borrow a wide variety of ideas from the fields of statistics and machine-learning, including recent uses of hidden Markov models, support vector machines, and artificial neural networks.</p>
                
  <h1 class="text-center top-space">About Apache Airavata</h1>
  <p dir="ltr"><A title="Apache Airavata"  href="http://airavata.apache.org/">Apache Airavata</A> 
is a software framework that enables you to compose, manage, execute, and monitor large scale applications and workflows on distributed computing resources such as local clusters, supercomputers, computational grids, and computing clouds. </p>
  <p dir="ltr"><A title="PHP Gateway with Airavata"  href="https://testdrive.airavata.org/">PGA</A>(PHP Gateway with Airavata) 
is a framework to build science gateway with the Airavata API. <A title="dREG gateway"  href="http://dreg.dnasequence.org">dREG gateway</A> is built on top of PGA by modifying it. </p>

</div>

<div class="post-edit"></div>  				               
  <h1 class="text-center top-space">Contact</h1>
  <div class="col-md-10 col-md-offset-1 text-center breathing-spaces">
      <div class="col-md-6">
          <span class="glyphicon glyphicon-envelope" style="font-size:6em;"></span><br>
          You can contact Gateway Admin by sending a mail to <a href="mailto:zw355@cornell.edu">Contact E-mail</a>
      </div>
      <div class="col-md-6">
          <span class="glyphicon glyphicon-edit" style="font-size:6em;"></span><br>
          You can also create a <span id="serviceDeskHelp">JIRA ticket</span> by signing in <a href="https://scigap.atlassian.net/servicedesk/customer/portal/8" target="_blank">here</a>.
     </div>
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

