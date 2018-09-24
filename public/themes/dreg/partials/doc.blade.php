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
Once a job is completed, the user can select 'Full results' in the drop-down list and then LEFT-click <B>'Download'</B> link in the experiment summary page to download a compressed file described in the <a href="#output" role="tab" data-toggle="tab">'output'</A> sheet in this page, or the user can download any single file from the drop-down list. The downloaded file with the 'tar.gz' extension can be decompressed by the 'tar' command, the file with the 'gz' extension can be decompressed by the 'gunzip' command in Linux. Please <font color="red">don't use RIGHT-click </font>  to open a tab for downloading.</br>
</br>
In <font color="RED">Safari</font>, it could be problematic because Safari tries to unzip the compressed results automatically using a non-compatible compress method. Please check <A href="https://octet.oberlin.edu/does-your-mac-unzip-zip-files-automatically/"> this link </A> to disable this feature.</p>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment summary" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.exp.summary.png"></img></div>

         <p class="description" style="padding:16px">
         8)&nbsp;&nbsp;<b>Switch to Genome Browser</b><br>
The convenient tool provided by the gateway is the user can check the results in the Genome Browser by clicking <b>'Switch to genome browser'</B> link. The genome identifier must be specified by two ways, 1) select from the drop-down list or 2) fill the identifier in the textbox. Please use LEFT-click to open a genome browser window.</p>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment summary" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.gbview.png"></img></div>

          <p class="description" style="padding:16px">
         9)&nbsp;&nbsp;<b>Check the storage</b><br>
The user can LEFT-click <b>'Open Folder'</b> link in the experiment summary page to check the storage for the current job or click the menu 'Storage' under the dREG logo to check the folders and files for all jobs(experiments). 
The following figure shows the data files in the job's folder, including two bigWig files, one result in bedgraph format, two outputs of job scheduler on GPU nodes.</p>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment summary" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.exp.folder.png"></img></div>

<a name="failure"></a> 
         <p class="description" style="padding:16px">
         10)&nbsp;&nbsp;<b>When you meet failure</b><br>

Currently when you run the dREG jobs, there are two types of errors you may have. One error may come from the system, called a system error, such as no computing time on specific GPU nodes or an internal errors in Apache Airavata. The other type of error is caused by the users' bigwig, called bigwig error, which can occur when read counts are normalized, each read is mapped to a region, or read counts in minus strand are positive values. The following figures show how to identify the error and how to handle it. </p>


         <p class="description" style="padding:16px">
         a)&nbsp;&nbsp;<b>System error</b><br>
When users submit the experiment, the failure will be shown in the experiment summary page soon as figure 10-S1 or 10-S2. The <b>experiment status</b> is "Failed" and many java errors are shown in the <b>"Errors"</b> item. Users can't solve this problem and should report this error the web master.</p>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="System error(1)" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/failure1.png"></img></div>
<div style="clear:both;text-align:center;"><center>Figure 10-S1</center></div>

<br>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="System error(2)" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/failure2.png"></img></div>
<div style="clear:both;text-align:center;"><center>Figure 10-S2</center></div>

<br>
         <p class="description" style="padding:16px">
         b)&nbsp;&nbsp;<b>Bigwig error</b><br>
After the experiment is complete, no results can be downloaded and job status shows a failure (see Figure 10-S3). Users can find the dREG log file or task log file to identify the problem. Enter into <b>"storage directory"</b> by clicking the <b>"open"</b> link. The users can find <b>"ARCHIVE"</b> folder where Apache Airavata copy back all files from the computing node. Check the dREG log file (<b>out.dREG.log</b>) to see the bigwig problem or check the task log file ("slurm-tasknoxxx.out") and find the reason why the task is aborted. Figure 10-S4 and 10-S5 give a two examples for this kind of error. If the bigwig has problems, please refer to this <A href="https://github.com/Danko-Lab/tutorials/blob/master/PRO-seq.md#read-mapping">link</a> to solve the problems.</p>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="Bigwig error" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/failure3.png"></img></div>
<div style="clear:both;text-align:center;"><center>Figure 10-S3</center></div>
<BR>

<p>This figure shows the bigWig problems in the dREG log file.</p>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="Bigwig error(1)" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/failure3-reason.png"></img></div>
<div style="clear:both;text-align:center;"><center>Figure 10-S4</center></div>
<BR>

<p>This figure shows the task log file in which explains the task was killed due to time limit.</p>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="Bigwig error(2)" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/failure4-reason.png"></img></div>
<div style="clear:both;text-align:center;"><center>Figure 10-S5</center></div>
<BR>

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
1) dREG run generates a compressed file including the <font color="green"> dREG results </font> as follows:
          </p>
<p class="description">&nbsp;</p>

          <table class="table">
              <tr>
                    <th>File name</th>
                    <th>Description</th>
              </tr>
              <tr>
                    <td>$PREFIX.dREG.infp.bed.gz</td>
                    <td>Informative positions with dREG scores predicted by the dREG model. Decompress it with 'gunzip' in Linux.</td>
              </tr>
              <tr>
                    <td>$PREFIX.dREG.peak.full.bed.gz</td>
                    <td>Significant peaks (FDR < 0.05) with dREG scores, p-values and center positions where the maximum dREG scores are located. Decompress it with 'gunzip' in Linux.</td>
              </tr>
              <tr>
                    <td>$PREFIX.dREG.peak.score.bed.gz</td>
                    <td>Significant peaks (FDR < 0.05) only with dREG scores. Decompress it with 'gunzip' in Linux.</td>
              </tr>

              <tr>
                    <td>$PREFIX.dREG.peak.prob.bed.gz</td>
                    <td>Significant peaks (FDR < 0.05) only with p-values. Decompress it with 'gunzip' in Linux.</td>
              </tr>

              <tr>
                    <td>$PREFIX.dREG.raw.peak.bed.gz</td>
                    <td>All raw peaks generated by dREG peak calling, including dREG scores, uncorrected p-values, center positions where the maximum are located in smoothed curves, center positions where the maximim are lcoated in original curve, centroid. Only available in the Web storage.</td>
              </tr>

              <tr>
                    <td>$PREFIX.tar.gz</td>
                    <td>Including above 5 files, can be decompressed by 'tar -xvzf' in Linux.</td>
             </tr>
            </table>
 <p class="description">
2) In the Web storage folder there are <font color="green">some files required by the WashU</font> genome browser:
</p>
<p class="description">&nbsp;</p>
          <table class="table">
              <tr>
                    <th>File name</th>                    <th>Description</th>              </tr>
              <tr>
                    <td>$PREFIX.dREG.infp.bw</td>
                    <td>The bigWig file converted from the bed file of informative positions ($PREFIX.dREG.infp.bed.gz).</td>
              </tr>
              <tr>
                    <td>$PREFIX.dREG.peak.score.bw</td>
                    <td>The bigWig file converted from the significant peaks (FDR < 0.05) with dREG scores ($PREFIX.dREG.peak.score.bed.gz).</td>
              </tr>
              <tr>
                    <td>$PREFIX.dREG.peak.prob.bw</td>
                    <td>The bigWig file converted from the significant peaks (FDR < 0.05) with p-values ($PREFIX.dREG.peak.prob.bed.gz).   </td>
              </tr>

              <tr>
                    <td>*.bed.gz.tbi</td>
                    <td> The index files generated from the corresponding bed files. Please ignore them if you download the results.</td>
              </tr>
         </table>

 <p class="description">
3) There are <font color="green">two log files </font> in the Web storage folder:</p>
<p class="description">&nbsp;</p>
        <table class="table">
              <tr>
                    <th>File name</th>                    <th>Description</th>              </tr>
              <tr>
                    <td>$PREFIX.dREG.log</td>
                    <td>Print the summary information after peak calling. If the bigWigs don't meet the requirements of dREG, the warning information will be outputted in this file.
                    </td>
              </tr>
              <tr>
                    <td>slurm-??????.out</td>
                    <td>The verbose logging output of dREG package.</td>
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
