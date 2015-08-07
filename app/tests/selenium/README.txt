###########################################################################
################JAVA TEST SCRIPTS FOR AIRAVATA PHP REFERENCE GATEWAY################
###########################################################################



###############Prerequisites To Run The Selenium Java Scripts##############
1. Firefox V 31.0 or 32.0 is required for the scripts.
https://support.mozilla.org/en-US/kb/install-older-version-of-firefox

2. Copy all PHP-Reference-Gateway application input files from 
https://iu.box.com/s/9ztdby709kso8siachz16svn2y511nn7 
4. Download the Selenium Java test project from

###########################################################################


###############Environments available for Running the Scripts##############
Selenium test are executed through PHP-Reference-Gateway by giving the URL
Current Production URLs
Base URL: http://test-drive.airavata.org/PHP-Reference-Gateway
Sub URL: index.php
###########################################################################


###################Script Alterations & Execution Steps###################
1. Add the Base URL and sub URL in to exp.properties.

2. Create a user login using the class; CreateUserLogin. Enter all your parameters and execute.

3. Change the config file with values prefered for 
		i.	Project Name
		ii. Project Description
		iii.Experiment Name Extension
		
2. In exp.properties file enter; 
	i. The correct base URL and sub URL of your working PPHP-Reference-Gateway.
	ii. Change the path of the input files (Where the files exists in your local machine) for the applications. 
	iii. The static part of the path is in the config file and the varying part is in utils file ExpFileReadUtils.
	
4. Execution Steps
		i.		CreateUserLogin.java - Already done above
		ii.		In UserLogin class change the username and password to your own username and password
		iii.	CreateModifySearchProject.java - Create A Project, Modify and Search for the Project
		iV.		Run Experiment creation scripts at your prefered sequence 
		OR
		V. 		Use RunAllTests and run all the applications at once. 
		VI.		If you want to do laod testing use ExpLoadTest class
		VII.	At the end run the SearchProjectExp.java to view your experiments (For the ease you can create all your experiments under one Project)
		VIII.	UserLogout.java
###########################################################################


############################Further Information############################
1. To record new Selenium scripts use Selenium IDE. Download from http://www.seleniumhq.org/download/

2. Record using IDE and export to java to modify and run

3. For clarifications, comments please use dev@airavata.apache.org or project@scigap.org

4. For more information on Selenium go to http://www.seleniumhq.org/docs/
###########################################################################


