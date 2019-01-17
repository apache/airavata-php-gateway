<div class="container">
  <div class="content">
    <div class="text-center">
      <h2 class="title top-space">dTOX Documentation</h2>            
    </div>

    <ul class="nav nav-tabs nav-justified" role="tablist">
      <li role="presentation" class="active"><a href="#instructure" role="tab" data-toggle="tab">Instructions</a></li>
      <li role="presentation"><a href="#input" role="tab" data-toggle="tab">Input</a></li>
      <li role="presentation"><a href="#output" role="tab" data-toggle="tab">Output</a></li>
      <li role="presentation"><a href="#faq" role="tab" data-toggle="tab">FAQ</a></li>
</ul>

    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="instructure">
      <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-xs-12">
          <p class="description" style="padding:16px">
         1)&nbsp;&nbsp;<b>Login</b>:(same as dREG)<br>
The user needs to log in by clicking 'login' link at the top-right corner of the page. Having an account provides a number of benefits, and is free and easy. 
          </p>
<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG login" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.login.png" ></img></div>


          <p class="description" style="padding:16px">
         2)&nbsp;&nbsp;<b>Create a new project (optional, same as dREG)</b><br>
Optionally, users can choose to make a new 'project' in the dREG/dTOX gateway to archive a collection of sequencing data from related experiments.  This will allow a collection of experiments to be stored in close proximity to each other.</p>
<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG project" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.create.project.png" ></img></div>

    

      <p class="description" style="padding:16px">
         3)&nbsp;&nbsp;<b>Start new dTOX</b><br>
Select the menu 'Start dREG/dTOX' below the dREG logo to create an data analysis for your data, as the following screenshot. Please notice to select the "dTOX prediction" Application. 
</p>
<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dtox.create.exp.png" ></img></div>

          <p class="description" style="padding:16px">
         4)&nbsp;&nbsp;<b>Fill experiment form</b><br>
Select bigWig files representing PRO-seq, ATAC-seq, or DNase-I-seq signal on the plus and minus strand.
          </p>

<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment create" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dtox.create.exp2.png" ></img></div>

          <p class="description" style="padding:16px">
         5)&nbsp;&nbsp;<b>Submit the job</b><br>
Click the 'save and launch' button.  BigWig file are transferred to the XSEDE server and a GPU queue is scheduled to run dTOX. After submitting, the user can check the status in the next web page, as shown below. Depending on the queue status, the job may wait for some time to start prediction. Once started, it will take 6-10 hours to complete depending on the genome used.</p>


          <p class="description" style="padding:16px">
         6)&nbsp;&nbsp;<b>Check the status</b><br>
The user can check the status of their 'experiment' by clicking the 'Saved runs' button on the top menu.
          </p>
<div style=" display: flex;justify-content: center"><img style="align-self: center;width:70%" alt="dREG experiment browse" src="{{ URL::to('/') }}/themes/{{Session::get('theme')}}/assets/img/dreg.exp.list.png"></img></div>

          <p class="description" style="padding:16px">
         7)&nbsp;&nbsp;<b>Check the results</b><br>
Once a job is completed, the user can select 'dTOX Bound Regions' in the drop-down list and then LEFT-click <B>'Download'</B> link in the experiment summary page to download a compressed file described in the <a href="#output" role="tab" data-toggle="tab">'output'</A> sheet in this page.  The downloaded file has a 'gz' extension and can be decompressed by the 'gunzip' command in Linux. Please <font color="red">don't use RIGHT-click </font>  to open a tab for downloading. To extract bound motifs for one specific transcription factor, download our R script (<A target=_blank href="https://github.com/Danko-Lab/dTOX/blob/master/extract_TF.bsh">here</A>)</br>
</br>
In <font color="RED">Safari</font>, it could be problematic because Safari tries to unzip the compressed results automatically using a non-compatible compression method. Please check <A href="https://octet.oberlin.edu/does-your-mac-unzip-zip-files-automatically/"> this link </A> to disable this feature.</p>

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
         10)&nbsp;&nbsp;<b>If your job fails</b><br>

When you run dTOX, there are two main types of errors you may encounter. One error may come from the system, called a system error, such as no computing time on specific GPU nodes or an internal errors in Apache Airavata. The other type of error is caused by the users' bigWig file, called a bigWig error, which can occur when read counts are normalized, each read is mapped to a region, or read counts in minus strand are positive values. The following figures show how to identify the error and how to handle it. </p>


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
After the experiment is complete, no results can be downloaded and job status shows a failure (see Figure 10-S3). Users can find the dTOX log file or task log file to identify the problem. Enter into <b>"storage directory"</b> by clicking the <b>"open"</b> link. The users can find <b>"ARCHIVE"</b> folder where Apache Airavata copies back all files from the computing node. Check the dTOX log file (<b>run.dTOX.log</b>) to see the bigwig problem or check the task log file ("slurm-tasknoxxx.out") and find the reason why the task was aborted. Figure 10-S4 and 10-S5 give two examples for this kind of error. If the bigwig has problems, please refer to the <A href="https://github.com/Danko-Lab/RunOnBamToBigWig">link for PRO-seq</a>, <A href="ht
tps://github.com/Danko-Lab/utils/dnase/BamToBigWig">link for DNase-I-seq</a>, or <A href="ht
tps://github.com/Danko-Lab/utils/atacseq/BamToBigWig">link for ATAC-seq</a> to solve the problems.</p>

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

<!---- INPUT PANEL -->

      <div role="tabpanel" class="tab-pane" id="input">
      <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-xs-12">

<p class="description" align="justify">The input to dTOX consists of two bigWig files which represent either the position of RNA polymerase on the positive and negative strands (PRO-seq) or the accessibility on the positive and negative strands (DNase-I-seq or ATAC-seq). The sequence alignment and processing steps to make the input bigWig files are a major factor influencing how accurately dTOX predicts transcription factor binding.</p>

<p class="description" align="justify">A key component of all datatypes is that data represents unnormalized raw counts. dTOX assumes that data represents the number of individual sequence tags that are located at each genomic position. For this reason, it is critical that input data is not normalized. The server checks to ensure that input data is expressed as integers, and will return an error if this is not the case.</p>

 
<p class="description"> Users can also use scripts generated in the Danko lab to create compatible bigWig files. Options for scripts at different starting points in the analysis are given below: </p>

<ul>
<li class="description"><b>Convert raw fastq files into bigWig</b>.<br/> 
<p class="description" align="justify">Our pipeline produces bigWig files that are compatible with dREG, and can be found at the following URLs: <A target=_blank href="https://github.com/Danko-Lab/proseq_2.0">https://github.com/Danko-Lab/proseq_2.0</A> (PRO-seq), <A target=_blank href="https://github.com/Danko-Lab/atac">https://github.com/Danko-Lab/atac</A> (ATAC-seq), <A target=_blank href="https://github.com/Danko-Lab/dnase">https://github.com/Danko-Lab/dnase</A> (DNase-I-seq). The pipelines automate routine pre-processing and alignment steps, including pre-processing reads to remove the adapter sequences and trim based on base quality, and deduplicate the reads if UMI barcodes are used. Sequencing reads are mapped to a reference genome using BWA. Aligned BAM files are converted into bigWig format in which each read is represented by a single base.</p>
</li>

<li><b>Convert mapped reads in BAM files into bigWigs</b>.<br/>
<p class="description" align="justify">We provide scripts that convert mapped reads from a BAM file into bigWig files that are compatible with dTOX. The scripts are avavailable on our GitHub page. For PRO-seq: <A target=_blank href="https://github.com/Danko-Lab/RunOnBamToBigWig">https://github.com/Danko-Lab/RunOnBamToBigWig</A>.  For DNase-I-seq: <A target=_blank href="https://github.com/Danko-Lab/utils/dnase/BamToBigWig">https://github.com/Danko-Lab/utils/dnase/BamToBigWig</A>.  For ATAC-seq: <A target=_blank href="https://github.com/Danko-Lab/utils/atacseq/BamToBigWig">https://github.com/Danko-Lab/utils/atacseq/BamToBigWig</A>.</p> 
</li>
</ul>
 
<p class="description">Other considerations:</p> 
<ul>
<p class="description" style="justify">The quality and quantity of the experimental data are major factors in determining how sensitive dTOX will be in detecting transcription factor binding. To increase the number of reads available for transcription factor binding detection, we encourage users to merge biological replicates in order to improve statistical power prior to running dTOX. Additionally, to compare binding predictions between conditions we recommend comparing samples at similar sequencing depths or down sampling to create similar sequencing depths.</p> 

<p class="description" style="justify">We have found that visualizing aligned data in a genome browser prior (e.g., IGV or UCSC) to downstream analysis is a useful way to catch any data quality or alignment issues.</p>

</ul>
        </div>
      </div>
    </div>

<!---- OUTPUT PANEL -->

      <div role="tabpanel" class="tab-pane" id="output">
      <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-xs-12">

          <p class="description">
1) A dTOX run generates a compressed file including the following files:
          </p>
<p class="description">&nbsp;</p>

          <table class="table">
              <tr>
                    <th>File name</th>
                    <th>Description</th>
              </tr>
              <tr>
                    <td>$PREFIX.dTOX.bound.bed.gz</td>
                    <td>TFBS regions that are predicted as bound. The file includes chromosome, start, ending, MOTIF ID, RTFBSDB score, strand, dTOX score, bound status. Decompress it with 'gunzip' in Linux.</td>
              </tr>
            </table>
 
<div style="padding:20px;
border-style: solid;
border-width: 5;
border-color: #dadada;" data-expandable-box-container="true">
<figcaption>
<div style="padding-bottom:15px" id="Sec2">Box 1:<b> Brief description of key terms</b></div>
</figcaption>

<div class="suppress-bottom-margin add-top-margin">
<p><b>Informative position:</b>
Loci denoted as "informative positions" meet the following criteria: contain more than 1 reads in 400 bp interval on either strand. Informative positions are used to predict transcription factor binding. </p>

<p><b>dTOX decision value:</b>
Training and prediction is done using a Support Vector Regression model where a label of 1 indicates transcription factor binding. The predicted values from the pre-trained model are called dTOX decision values. A dTOX decision value close to 1 indicates that a position likely to be bound. 
</p>
</div></div>

<br/>

<div style="padding:20px;
border-style: solid;
border-width: 5;
border-color: #dadada;" data-expandable-box-container="true">
<figcaption>
<div style="padding-bottom:15px" id="Sec2">Box 2:<b> Extracting bound motifs for a specific transcription factor. </b></div>
</figcaption>

<div class="suppress-bottom-margin add-top-margin">
<p>The dTOX output file contains the binding status of our entire set of motifs with PWMs. To find the binding status of the motifs you are interested in, you can run our R script that extracts the Motif IDs that belong to a particular transcription factor. The script is located <A target=_blank href="https://github.com/Danko-Lab/dTOX/blob/master/extract_TF.bsh">here.</A> This script requires 3 arguments: the name of the file with the dTOX results, the transcription factor you want to extract, and an output file name. To run this script on Unix or Linux, you need to use the following command:
<br/>
<br/>
R --vanilla --slave --args out.dTOX.bound.bed.gz TF outputFile.bed.gz < extract-bound-TF.R 

</p>
</div></div>

<br/>




<p class="description">
2) In the Web storage folder there are <font color="green">some files required by the WashU</font> genome browser:
</p>
<p class="description">&nbsp;</p>
          <table class="table">
              <tr>
                    <th>File name</th>                    <th>Description</th>              </tr>
              <tr>
              <tr>
                    <td>$PREFIX.dTOX.bound.bw</td>
                    <td>The bigWig file converted from bound motifs ($PREFIX.dTOX.bound.bed.gz).</td>
              </tr>
              <tr>      
                    <td>*.bed.gz.tbi</td>
                    <td> The index files generated from the corresponding bed files. Please ignore them if you download the results.</td>
              </tr>
         </table>

 <p class="description">
3) There are <font color="green">one log file </font> in the Web storage folder:</p>
<p class="description">&nbsp;</p>
        <table class="table">
              <tr>
                    <th>File name</th>                    <th>Description</th>              </tr>
              <tr>
                    <td>slurm-??????.out</td>
                    <td>The verbose log output of dTOX package.</td>
             </tr>
         </table>
         </div>
      </div>
    </div>

<!---- FAQ PANEL -->


      <div role="tabpanel" class="tab-pane" id="faq">
      <div class="row">
        <div class="col-sm-offset-1 col-sm-10 col-xs-12">

<p>dREG Gateway is online service that supports Web-based science through the execution of online computational experiments and the management of data. Below are frequent questions about the dREG Gateway and the dTOX program.</p>

<p><b>Q: How should I prepare bigWig files for use with dTOX?</b></p>
<p>A: Information about how to prepare files can be found on the Danko lab github page here for<A href="https://github.com/Danko-Lab/proseq2.0"> PRO-seq </A>, <A href="https://github.com/Danko-Lab/utils/tree/master/dnase"> DNase </A>, and <A href="https://github.com/Danko-Lab/utils/tree/master/atacseq"> ATAC-seq </A>.</p>

<p><b>Q: How should I do when I meet the computational failure in the dREG gateway?</b></p>
<p>A: There are two types of error you may have, we explain how to identify your error and how to handle it <A href="https://dreg.dnasequence.org/pages/doc#failure"> here</A>.</p>

<p><b>Q: Which browser works well with the dREG gateway?</b></p>
<p>A: We have tested in the Firefox, Google Chrome and Safari so far. For IE (version 10 or 11) and some version of Safari, you maybe have trouble showing sequence data in WashU genome browser. For Safari users, please read next Q&A.</p>


<p><b>Q: What should the Safari users be aware of?</b></p>
<p>A: By default, Safari unzips a zip file automatically when you download it. However dTOX results are compressed by the 'bgzip' command which is not compatiable with the Safari method. It would be problematic when you download dTOX results. Please refer to <A href="https://octet.oberlin.edu/does-your-mac-unzip-zip-files-automatically/"> this link </A> to disable this feature in Safari and then download the compressed results from dREG gateway. </br>
Secondly, when you click the genome browser link, please use the Left-Click, don't use Right-Click menu and the menu option "open a new tab".
</p>

<p><b>Q: Will dTOX work with my data type?</b></p>
<p>A: dTOX was trained and tested on PRO-seq, ATAC-seq, and DNase-I-seq. dTOX will also work well with data collected by any run-on and sequencing method, including GRO-seq, PRO-seq, or ChRO-seq. Other methods that map the location of RNA polymerase genome wide using alternative tools (for example, NET-seq) will most likely work well, but are not officially supported.</p>

<p><b>Q: Will the pre-trained models work using data from my species?</b></p>
<p>A: Models are currently available only in mammalian organisms.  The length and density of genes, which vary considerably between highly divergent species, affects the way that a transcribed promoter or enhancer looks.  For this reason, models can only be used in species.  We are working to create models in widely-used model organisms, including drosophila and C. elegans. </p>

<p><b>Q: How deeply do I need to sequence PRO-seq libraries?</b></p>
<p>A: Sensitivity is reasonable at ~40 million mapped reads and saturates at ~100 million mapped reads.  See our analysis here: <A href="http://www.nature.com/nmeth/journal/v12/n5/fig_tab/nmeth.3329_SF3.html">supplementary figure 3 in dREG paper</A>.</p>

<p><b>Q: How long do my data and results keep in the dREG gateway?</b></p>
<p>A: One month.</p>

<p><b>Q: How do I cite dTOX?</b></p>
<p>A: Please cite our papers if you use dTOX results in your publication:<BR/>
<A target="_blank" href="https://www.biorxiv.org/content/early/2018/05/14/321539.abstract">
(1) ADD CITATION. Choate, L. A., Wang, Z., & Danko, C. G. (2018). Identification of transcription factor binding using genome-wide accessibility and transcription. bioRxiv. </A></P>

<p><b>Q: Do I have to create account before using this service?</b></p>
<p>A: Yes, this system is supported by an NSF funded supercomputing resource known as <A title="XSEDE" href="http://www.xsede.org">XSEDE</A>, who regularly needs to report bulk usage statistics to NSF.  Nevertheless, data that you provide are completely safe.</p>

<p><b>Q: How do I know the status of the computational nodes? </b></p>
<p>A: Since we can't update this web site very often, the gateway status is updated <A target=_blank href="https://github.com/Danko-Lab/dREG/blob/master/gateway-update.md">here</A> on the dREG page based on the notifications of the XSEDE community. </p>

<p><b>Q: Who do I thank for the computing power? </b></p>
<p>A: This web-based tool is powered by <A title="SciGaP" href="http://www.scigap.org">SciGaP</A> and <A title="Apache Airavata"  href="http://airavata.apache.org/">Apache Airavata</A> and the GPU servers are supported by the <A title="XSEDE" href="http://www.xsede.org">XSEDE</A>.</p>
   
<p><b>Q: I have another question that is not on this FAQ.  How can I contact you?</b></p>
<p>A: Yes, please contact us with any questions! Zhong(zw355 at cornell.edu).  Charles(cgd24 at cornell.edu).</p>


   </div>
  </div>
</div>

<!---- OUTPUT PANEL -->

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
