<?php

//Airavata classes - loaded from app/libraries/Airavata
use Airavata\Model\AppCatalog\AppDeployment\ApplicationDeploymentDescription;
use Airavata\Model\AppCatalog\AppDeployment\ApplicationModule;
use Airavata\Model\AppCatalog\AppDeployment\ApplicationParallelismType;
use Airavata\Model\AppCatalog\AppDeployment\SetEnvPaths;
use Airavata\Model\AppCatalog\AppInterface\ApplicationInterfaceDescription;
use Airavata\Model\AppCatalog\AppInterface\DataType;
use Airavata\Model\AppCatalog\AppInterface\InputDataObjectType;
use Airavata\Model\AppCatalog\AppInterface\OutputDataObjectType;


class AppUtilities
{

    public static function create_or_update_appModule($inputs, $update = false)
    {

        $appModule = new ApplicationModule(array(
            "appModuleName" => $inputs["appModuleName"],
            "appModuleVersion" => $inputs["appModuleVersion"],
            "appModuleDescription" => $inputs["appModuleDescription"]
        ));

        if ($update)
            return Airavata::updateApplicationModule(Session::get('authz-token'), $inputs["appModuleId"], $appModule);
        else
            return Airavata::registerApplicationModule(Session::get('authz-token'), Session::get("gateway_id"), $appModule);
    }

    public static function deleteAppModule($appModuleId)
    {

        return Airavata::deleteApplicationModule(Session::get('authz-token'), $appModuleId);
    }

    public static function getAppInterfaceData()
    {

        $modules = AppUtilities::getAllModules();
        $appInterfaces = Airavata::getAllApplicationInterfaces(Session::get('authz-token'), Session::get("gateway_id"));

        return array(
            "appInterfaces" => $appInterfaces,
            "dataTypes" => \Airavata\Model\Application\Io\DataType::$__names,
            "modules" => $modules
        );
    }

    public static function create_or_update_appInterface($appInterfaceValues, $update = false)
    {

        //var_dump( $appInterfaceValues); exit;
        $appInterface = new ApplicationInterfaceDescription(array(
            "applicationName" => $appInterfaceValues["applicationName"],
            "applicationDescription" => $appInterfaceValues["applicationDescription"],
            "applicationModules" => $appInterfaceValues["applicationModules"]
        ));

        if (isset($appInterfaceValues["inputName"])) {
            foreach ($appInterfaceValues["inputName"] as $index => $name) {
                $inputDataObjectType = new InputDataObjectType(array(
                    "name" => $name,
                    "value" => $appInterfaceValues["inputValue"][$index],
                    "type" => $appInterfaceValues["inputType"][$index],
                    "applicationArgument" => $appInterfaceValues["applicationArgumentInput"][$index],
                    "standardInput" => $appInterfaceValues["standardInput"][$index],
                    "userFriendlyDescription" => $appInterfaceValues["userFriendlyDescription"][$index],
                    "metaData" => $appInterfaceValues["metaData"][$index],
                    "inputOrder" => intval($appInterfaceValues["inputOrder"][$index]),
                    "dataStaged" => intval($appInterfaceValues["dataStaged"][$index]),
                    "isRequired" => $appInterfaceValues["isRequiredInput"][$index],
                    "requiredToAddedToCommandLine" => $appInterfaceValues["requiredToAddedToCommandLineInput"][$index]
                ));
                $appInterface->applicationInputs[] = $inputDataObjectType;
            }
        }

        if (isset($appInterfaceValues["outputName"])) {
            foreach ($appInterfaceValues["outputName"] as $index => $name) {
                $outputDataObjectType = new OutputDataObjectType(array(
                    "name" => $name,
                    "value" => $appInterfaceValues["outputValue"][$index],
                    "type" => $appInterfaceValues["outputType"][$index],
                    "applicationArgument" => $appInterfaceValues["applicationArgumentOutput"][$index],
                    "dataMovement" => intval($appInterfaceValues["dataMovement"][$index]),
                    "location" => $appInterfaceValues["location"][$index],
                    "isRequired" => $appInterfaceValues["isRequiredOutput"][$index],
                    "requiredToAddedToCommandLine" => $appInterfaceValues["requiredToAddedToCommandLineOutput"][$index],
                    "searchQuery" => $appInterfaceValues["searchQuery"][$index]
                ));
                $appInterface->applicationOutputs[] = $outputDataObjectType;
            }
        }

        //var_dump( $appInterface); exit;

        if ($update) {
            if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
                if (Cache::has('APP-' . $appInterfaceValues["app-interface-id"])) {
                    Cache::forget('APP-' . $appInterfaceValues["app-interface-id"]);
                }
            }
            Airavata::updateApplicationInterface(Session::get('authz-token'), $appInterfaceValues["app-interface-id"], $appInterface);
        } else {
            Airavata::getApplicationInterface(Session::get('authz-token'), Airavata::registerApplicationInterface(Session::get('authz-token'), Session::get("gateway_id"), $appInterface));
        }
        //print_r( "App interface has been created.");
    }

    public static function deleteAppInterface($appInterfaceId)
    {
        if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
            if (Cache::has('APP-' . $appInterfaceId)) {
                Cache::forget('APP-' . $appInterfaceId);
            }
        }
        return Airavata::deleteApplicationInterface(Session::get('authz-token'), $appInterfaceId);
    }


    public static function getAppDeploymentData()
    {

        $appDeployments = Airavata::getAllApplicationDeployments(Session::get('authz-token'), Session::get("gateway_id"));
        //var_dump( $appDeployments); exit;
        $computeResources = Airavata::getAllComputeResourceNames(Session::get('authz-token'));
        $modules = AppUtilities::getAllModules();
        $apt = new ApplicationParallelismType();

        return array(
            "appDeployments" => $appDeployments,
            "applicationParallelismTypes" => $apt::$__names,
            "computeResources" => $computeResources,
            "modules" => $modules
        );
    }

    public static function create_or_update_appDeployment($inputs, $update = false)
    {

        $appDeploymentValues = $inputs;

        if (isset($appDeploymentValues["moduleLoadCmds"]))
            $appDeploymentValues["moduleLoadCmds"] = array_unique(array_filter($appDeploymentValues["moduleLoadCmds"]));

        if (isset($appDeploymentValues["libraryPrependPathName"])) {
            $libPrependPathNames = array_unique(array_filter($appDeploymentValues["libraryPrependPathName"], "trim"));

            foreach ($libPrependPathNames as $index => $prependName) {
                $envPath = new SetEnvPaths(array(
                    "name" => $prependName,
                    "value" => $appDeploymentValues["libraryPrependPathValue"][$index]
                ));
                $appDeploymentValues["libPrependPaths"][] = $envPath;
            }
        }

        if (isset($appDeploymentValues["libraryAppendPathName"])) {
            $libAppendPathNames = array_unique(array_filter($appDeploymentValues["libraryAppendPathName"], "trim"));
            foreach ($libAppendPathNames as $index => $appendName) {
                $envPath = new SetEnvPaths(array(
                    "name" => $appendName,
                    "value" => $appDeploymentValues["libraryAppendPathValue"][$index]
                ));
                $appDeploymentValues["libAppendPaths"][] = $envPath;
            }
        }

        if (isset($appDeploymentValues["environmentName"])) {
            $environmentNames = array_unique(array_filter($appDeploymentValues["environmentName"], "trim"));
            foreach ($environmentNames as $index => $envName) {
                $envPath = new SetEnvPaths(array(
                    "name" => $envName,
                    "value" => $appDeploymentValues["environmentValue"][$index]
                ));
                $appDeploymentValues["setEnvironment"][] = $envPath;
            }
        }

        if (isset($appDeploymentValues["preJobCommand"])) {
            $appDeploymentValues["preJobCommands"] = array_unique(array_filter($appDeploymentValues["preJobCommand"], "trim"));
        }

        if (isset($appDeploymentValues["postJobCommand"])) {
            $appDeploymentValues["postJobCommands"] = array_unique(array_filter($appDeploymentValues["postJobCommand"], "trim"));
        }

        //var_dump( $appDeploymentValues); exit;
        $appDeployment = new ApplicationDeploymentDescription($appDeploymentValues);
        if ($update)
            Airavata::updateApplicationDeployment(Session::get('authz-token'), $inputs["app-deployment-id"], $appDeployment);
        else
            $appDeploymentId = Airavata::registerApplicationDeployment(Session::get('authz-token'), Session::get("gateway_id"), $appDeployment);

        return;

    }

    public static function deleteAppDeployment($appDeploymentId)
    {
        return Airavata::deleteApplicationDeployment(Session::get('authz-token'), $appDeploymentId);
    }

    public static function getAllModules()
    {
        return Airavata::getAllAppModules(Session::get('authz-token'), Session::get("gateway_id"));
    }

    /**
     * Get all available applications
     * @return null
     */
    public static function get_all_applications()
    {
        $applications = null;

        try {
            $applications = Airavata::getAllApplicationInterfaceNames(Session::get('authz-token'), Session::get("gateway_id"));
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem getting all applications.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem getting all applications.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_warning_message('<p>You must create an application module, interface and deployment space before you can create an experiment.
                Click <a href="' . URL::to('/') . '/app/module">here</a> to create an application.</p>');
            /*
            CommonUtilities::print_error_message('<p>There was a problem getting all applications.
                Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
                */
        }

        if (count($applications) == 0)
            CommonUtilities::print_warning_message('<p>You must create an application module, interface and deployment space before you can create an experiment.
                Click <a href="' . URL::to('/') . '/app/module">here</a> to create an application.</p>');


        return $applications;
    }

    /**
     * Get the interface for the application with the given ID
     * @param $id
     * @return null
     */
    public static function get_application_interface($id)
    {
        $applicationInterface = null;

        try {
            if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
                if (Cache::has('APP-' . $id)) {
                    return Cache::get('APP-' . $id);
                } else {
                    $applicationInterface = Airavata::getApplicationInterface(Session::get('authz-token'), $id);
                    Cache::put('APP-' . $id, $applicationInterface, Config::get('pga_config.airavata')['app-catalog-cache-duration']);
                    return $applicationInterface;
                }
            } else {
                return $applicationInterface = Airavata::getApplicationInterface(Session::get('authz-token'), $id);
            }


        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem getting the application interface.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem getting the application interface.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('<p>There was a problem getting the application interface.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
        }
    }

    /**
     * Get a list of the inputs for the application with the given ID
     * @param $id
     * @return null
     */
    public static function get_application_inputs($id)
    {
        $inputs = null;

        try {
            $inputs = Airavata::getApplicationInputs(Session::get('authz-token'), $id);
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem getting application inputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem getting application inputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('<p>There was a problem getting application inputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
        }

        return $inputs;
    }


    /**
     * Get a list of the outputs for the application with the given ID
     * @param $id
     * @return null
     */
    public static function get_application_outputs($id)
    {
        $outputs = null;

        try {
            $outputs = Airavata::getApplicationOutputs(Session::get('authz-token'), $id);
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem getting application outputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem getting application outputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('<p>There was a problem getting application outputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
        }

        return $outputs;
    }


}