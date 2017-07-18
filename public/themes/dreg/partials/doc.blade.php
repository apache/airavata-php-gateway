<div class="container">
  <div class="content">
    <div class="text-center">
      <h2 class="title top-space">Documents</h2>            
    </div>

    <ul class="nav nav-tabs nav-justified" role="tablist">
      <li role="presentation" class="active"><a href="#instructure" role="tab" data-toggle="tab">Instructions</a></li>
      <li role="presentation"><a href="#sp" role="tab" data-toggle="tab">Software/Package</a></li>
      <li role="presentation"><a href="#output" role="tab" data-toggle="tab">Output</a></li>
    </ul>

    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="instructure">
      <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-xs-12">
          <p class="description" style="padding:16px">
         1)&nbsp;&nbsp;<b>Login</b>:<br>
The user needs to log in by clicking 'login' link at the top-right corner of the page. Having an account provides a number of benefits, and is free and easy. 
          </p>
<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG login" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.login.png" ></img></div>


          <p class="description" style="padding:16px">
         2)&nbsp;&nbsp;<b>Create a new project (optional)</b><br>
Optionally, users can choose to make a new 'project' in the dREG gateway to archive a collection of dREG data from related experiments.  This will allow a collection of experiments to be stored in close proximity to each other.</p>
<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG project" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.create.project.png" ></img></div>

    

      <p class="description" style="padding:16px">
         3)&nbsp;&nbsp;<b>Start new dREG</b><br>
Select the menu 'Start dREG' below the dREG logo to create an data analysis for your data, as the following screenshot.
</p>
<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.create.exp.png" ></img></div>

          <p class="description" style="padding:16px">
         4)&nbsp;&nbsp;<b>Select bigWig files</b><br>
Select bigWig files representing PRO-seq, GRO-seq, or ChRO-seq signal on the plus and minus strand. Please notice that two GPU resources are available now, currently it is easier to get the computation resources on <A href="http://comet.sdsc.xsede.org/">Comet.sdsc.xsede.org</A> than <A href="https://www.psc.edu/index.php/bridges">Bridges.psc.edu</A>. 
          </p>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment create" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.create.exp2.png" ></img></div>

          <p class="description" style="padding:16px">
         5)&nbsp;&nbsp;<b>Submit the job</b><br>
Click the 'save and launch' button.  BigWig file are transferred to the XSEDE server and a GPU queue is scheduled to run dREG. After submitting, the user can check the status in the next web page, as shown below. Depend on the queue status, the job maybe wait for a long time to start prediction. Once started, it will only take 1-4 hours to complete.</p>


          <p class="description" style="padding:16px">
         6)&nbsp;&nbsp;<b>Check the status</b><br>
The user can check the status of their 'experiment' by clicking the menu 'Saved dREG runs' below the dREG logo.
          </p>
<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment browse" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.exp.list.png"></img></div>

          <p class="description" style="padding:16px">
         7)&nbsp;&nbsp;<b>Check the results</b><br>
Once a job is completed, the user can click <B>'Download All Results'</B> link in the experiment summary page to download a compressed file described in the <a href="#output" role="tab" data-toggle="tab">'output'</A> sheet in this page, or the user can download any single file from the drop-down list. 
          
</p>
<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment summary" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.exp.summary.png"></img></div>

         <p class="description" style="padding:16px">
         8)&nbsp;&nbsp;<b>Switch to Genome Browser</b><br>
The convenient tool ptovided by the gateway is the user can check the results in the Genome Browser by clicking <b>'Genome Browser'</B> link. </p>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment summary" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.gbview.png"></img></div>

          <p class="description" style="padding:16px">
         9)&nbsp;&nbsp;<b>Check the storage</b><br>
the user can click <b>'Open Folder'</b> link in the experiment summary page to check the storage for the current job or click the menu 'Storage' under the dREG logo to check the folders and files for all jobs(experiments). The following figure shows the data files in the job's folder, including two bigWig files, one result in bedgraph format, two outputs of job scheduler on GPU nodes.</p>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment summary" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.exp.folder.png"></img></div>


        </div>
      </div>

    </div>

      <div role="tabpanel" class="tab-pane" id="sp">
      <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-xs-12">
          <p class="description">The <B>dREG gateway</B> is web service built on the Apache Airavata software framework and the XSEDE platform using the following software packages:</p>

          <p class="description">[1] <B>dREG package</B>: <A href="https://github.com/Danko-Lab/dREG">https://github.com/Danko-Lab/dREG</A>.</p>
          <p class="description"> 
The dREG package is developed to detect the divergently oriented RNA polymerase in GRO-seq, PRO-seq, or ChRO-seq data using support vector machines (e1070 or Rgtsvm package).</p>
          <p class="description">[2] <B>dREG.HD package</B>: <A href="https://github.com/Danko-Lab/dREG.HD">https://github.com/Danko-Lab/dREG.HD</A>.</p>
          <p class="description">The dREG.HD package refines the location of TREs obtained using dREG by imputing DNAse-I hypersensitivity.</p>
          <p class="description">[3] <B>Rgtsvm package</B>: <A href="https://github.com/Danko-Lab/Rgtsvm">https://github.com/Danko-Lab/Rgtsvm</A>.</p>
          <p class="description">
Rgtsvm implements support vector classification and support vector regression on a GPU to accelerate the computational speed of training and predicting large-scale models. </p>
          
          <p class="description">[4] <B>Airavata PHP Gateway</B>: <A href="https://github.com/apache/airavata-php-gateway.git">https://github.com/apache/airavata-php-gateway.git</A>.</p>
          <p class="description">
Airavata PHP Gateway provides an API to build web sites which interact with high performance computers that are part of XSEDE.
</p>

        </div>
      </div>
    </div>

      <div role="tabpanel" class="tab-pane" id="output">
      <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-xs-12">

          <p class="description">
dREG run generates a compressed file including the dREG and dREG.HD results as follows:
          </p>
<p class="description">&nbsp;</p>

          <table class="table">
              <tr>
                    <th>File name</th>
                    <th>Description</th>
              </tr>
              <tr>
                    <td>out.dREG.pred.gz</td>
                    <td>Informative positions with the scores predicted by the dREG model</td>
              </tr>
              <tr>
                    <td>out.dREG.peak.gz</td>
                    <td>dREG peaks called using the threshold 0.8 </td>
              </tr>
              <tr>
                    <td>out.dREG.HD.imputedDnase.bw</td>
                    <td>The imputed DNase-I signal called by dREG.HD.</td>
              </tr>
              <tr>
                    <td>out.dREG.HD.relaxed.bed</td>
                    <td>dREG.HD peaks called under relaxed condition (FDR=16%)</td>
              </tr>
              <tr>
                    <td>out.dREG.HD.stringent.bed</td>
                    <td>dREG.HD peaks called under stringent condition (FDR=10%)</td>
              </tr>
        </table>

        </div>
      </div>
    </div>


  </div><!-- /.content -->

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
