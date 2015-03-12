<?php

//Airavata classes - loaded from app/libraries/Airavata
use Airavata\API\AiravataClient;

use Airavata\Model\AppCatalog\AppInterface\DataType;
use Airavata\Model\AppCatalog\AppInterface\InputDataObjectType;
use Airavata\Model\AppCatalog\AppInterface\OutputDataObjectType;
use Airavata\Model\AppCatalog\AppInterface\ApplicationInterfaceDescription;

use Airavata\Model\Workspace\Project;

use Airavata\Model\AppCatalog\AppDeployment\ApplicationModule;
use Airavata\Model\AppCatalog\AppDeployment\ApplicationParallelismType;
use Airavata\Model\AppCatalog\AppDeployment\ApplicationDeploymentDescription;
use Airavata\Model\AppCatalog\AppDeployment\SetEnvPaths;

//use Airavata\Model\AppCatalog\ComputeResource


class AppUtilities{

	public static function create_or_update_appModule( $inputs, $update = false){

		$airavataclient = Session::get("airavataClient");

		$appModule = new ApplicationModule( array(
												"appModuleName" => $inputs["appModuleName"],
												"appModuleVersion" => $inputs["appModuleVersion"],
												"appModuleDescription" => $inputs["appModuleDescription"]
										));
		
		if( $update)
			return $airavataclient->updateApplicationModule( $inputs["appModuleId"], $appModule);
		else
			return $airavataclient->registerApplicationModule( Session::get("gateway_id"), $appModule);
	}

	public static function deleteAppModule( $appModuleId){

		$airavataclient = Session::get("airavataClient");

		return $airavataclient->deleteApplicationModule( $appModuleId);
	}

	public static function getAppInterfaceData(){

		$airavataclient = Session::get("airavataClient");

		$dataType = new DataType();
		$modules = AppUtilities::getAllModules();
		$appInterfaces = $airavataclient->getAllApplicationInterfaces( Session::get("gateway_id"));


		$InputDataObjectType = new InputDataObjectType();

		return array(
						"appInterfaces" 	=> $appInterfaces,
						"dataTypes" 		=> $dataType::$__names,
						"modules"   		=> $modules
						);
	}

	public static function create_or_update_appInterface( $appInterfaceValues, $update = false){
		
		$airavataclient = Session::get("airavataClient");
		//var_dump( $appInterfaceValues); exit;
		$appInterface = new ApplicationInterfaceDescription( array(
																"applicationName" => $appInterfaceValues["applicationName"],
																"applicationDescription" => $appInterfaceValues["applicationDescription"],
																"applicationModules" => $appInterfaceValues["applicationModules"]
															) ); 

		if( isset( $appInterfaceValues["inputName"]))
		{
			foreach ($appInterfaceValues["inputName"] as $index => $name) {
				$inputDataObjectType = new InputDataObjectType( array(
																	"name" => $name,
																	"value" => $appInterfaceValues["inputValue"][ $index],
																	"type" => $appInterfaceValues["inputType"][ $index],
																	"applicationArgument" => $appInterfaceValues["applicationArgumentInput"][$index],
																	"standardInput" => $appInterfaceValues["standardInput"][ $index],
																	"userFriendlyDescription" => $appInterfaceValues["userFriendlyDescription"][ $index],
																	"metaData" => $appInterfaceValues["metaData"][ $index],
																	"inputOrder" => intval( $appInterfaceValues["inputOrder"][ $index]),
																	"dataStaged" => intval( $appInterfaceValues["dataStaged"][ $index]),
																	"isRequired" => $appInterfaceValues["isRequiredInput"][ $index],
																	"requiredToAddedToCommandLine" => $appInterfaceValues["requiredToAddedToCommandLineInput"][$index]
																) );
				$appInterface->applicationInputs[] = $inputDataObjectType;
			}
		}

		if( isset( $appInterfaceValues["outputName"]))
		{
			foreach ( $appInterfaceValues["outputName"] as $index => $name) {
				$outputDataObjectType = new OutputDataObjectType( array(
																	"name" => $name,
																	"value" => $appInterfaceValues["outputValue"][ $index],
																	"type" => $appInterfaceValues["outputType"][ $index],
																	"applicationArgument" => $appInterfaceValues["applicationArgumentOutput"][$index],
																	"dataMovement" => intval( $appInterfaceValues["dataMovement"][ $index]),
																	"location" => $appInterfaceValues["location"][ $index],
																	"isRequired" => $appInterfaceValues["isRequiredOutput"][ $index],
																	"requiredToAddedToCommandLine" => $appInterfaceValues["requiredToAddedToCommandLineOutput"][$index],
																	"searchQuery" => $appInterfaceValues["searchQuery"][$index]
																));
				$appInterface->applicationOutputs[] = $outputDataObjectType;
			}
		}

		//var_dump( $appInterface); exit;

		if( $update)
			$airavataclient->updateApplicationInterface( $appInterfaceValues["app-interface-id"], $appInterface);
		else
			$airavataclient->getApplicationInterface($airavataclient->registerApplicationInterface( $appInterface) );

		//print_r( "App interface has been created.");
	}

	public static function deleteAppInterface( $appInterfaceId){

		$airavataclient = Session::get("airavataClient");

		return $airavataclient->deleteApplicationInterface( $appInterfaceId);
	}


	public static function getAppDeploymentData(){

		$airavataclient = Session::get("airavataClient");

		$appDeployments = $airavataclient->getAllApplicationDeployments( Session::get("gateway_id"));
		//var_dump( $appDeployments); exit;
		$computeResources = $airavataclient->getAllComputeResourceNames();
		$modules = AppUtilities::getAllModules();
		$apt = new ApplicationParallelismType();

		return array( 
						"appDeployments" 			  => $appDeployments,
						"applicationParallelismTypes" => $apt::$__names,
						"computeResources"            => $computeResources,
						"modules"			          => $modules
					);
	}

	public static function create_or_update_appDeployment( $inputs, $update = false){

		$appDeploymentValues = $inputs;

		$airavataclient = Session::get("airavataClient");

		if( isset( $appDeploymentValues["moduleLoadCmds"]))
			$appDeploymentValues["moduleLoadCmds"] = array_unique( array_filter( $appDeploymentValues["moduleLoadCmds"]));

		if( isset( $appDeploymentValues["libraryPrependPathName"] )) 
		{	
			$libPrependPathNames = array_unique( array_filter( $appDeploymentValues["libraryPrependPathName"],"trim" ));
		
			foreach( $libPrependPathNames as $index => $prependName)
			{
				$envPath = new SetEnvPaths(array(
												"name" => $prependName,
												"value" => $appDeploymentValues["libraryPrependPathValue"][ $index]
											));
				$appDeploymentValues["libPrependPaths"][] = $envPath;
			}
		}

		if( isset( $appDeploymentValues["libraryAppendPathName"] )) 
		{
			$libAppendPathNames = array_unique( array_filter( $appDeploymentValues["libraryAppendPathName"],"trim" ));
			foreach( $libAppendPathNames as $index => $appendName)
			{
				$envPath = new SetEnvPaths(array(
												"name" => $appendName,
												"value" => $appDeploymentValues["libraryAppendPathValue"][ $index]
											));
				$appDeploymentValues["libAppendPaths"][] = $envPath;
			}
		}

		if( isset( $appDeploymentValues["environmentName"] )) 
		{
			$environmentNames = array_unique( array_filter( $appDeploymentValues["environmentName"], "trim"));
			foreach( $environmentNames as $index => $envName)
			{
				$envPath = new SetEnvPaths(array(
												"name" => $envName,
												"value" => $appDeploymentValues["environmentValue"][$index]
											));
				$appDeploymentValues["setEnvironment"][] = $envPath;
			}
		}
		
		if( isset( $appDeploymentValues["preJobCommand"] )) 
		{
			$appDeploymentValues["preJobCommands"] = array_unique( array_filter( $appDeploymentValues["preJobCommand"], "trim"));
		}

		if( isset( $appDeploymentValues["postJobCommand"] )) 
		{
			$appDeploymentValues["postJobCommands"] = array_unique( array_filter( $appDeploymentValues["postJobCommand"], "trim"));
		}

		//var_dump( $appDeploymentValues); exit;
		$appDeployment = new ApplicationDeploymentDescription(  $appDeploymentValues);
		if( $update)
			$airavataclient->updateApplicationDeployment( $inputs["app-deployment-id"], $appDeployment);
		else
			$appDeploymentId = $airavataclient->registerApplicationDeployment( Session::get("gateway_id"), $appDeployment);

		return;

	}

	public static function deleteAppDeployment( $appDeploymentId )
	{

		$airavataclient = Session::get("airavataClient");

		return $airavataclient->deleteApplicationDeployment( $appDeploymentId);
	}

	public static function getAllModules(){
		$airavataclient = Session::get("airavataClient");
		return $airavataclient->getAllAppModules( Session::get("gateway_id"));
	}
}