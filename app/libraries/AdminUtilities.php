<?php

use Airavata\Model\Workspace\Gateway;

class AdminUtilities
{

    /**
     * To create a new gateway
     * @param $input
     * @return string
     */
    public static function add_gateway($input)
    {
        $gateway = new Gateway();
        $gateway->gatewayId = $input["gatewayName"];
        $gateway->domain = $input["domain"];
        $gateway->gatewayName = $input["gatewayName"];
        $gateway->emailAddress = $input["admin-email"];
        return Airavata::addGateway(Session::get('authz-token'), $gateway);
    }

    /**
     * Method to get experiment execution statistics object
     * @param $fromTime
     * @param $toTime
     * @return \Airavata\Model\Experiment\ExperimentStatistics
     */
    public static function get_experiment_execution_statistics($fromTime, $toTime)
    {
        return Airavata::getExperimentStatistics(Session::get('authz-token'),
            Config::get('pga_config.airavata')['gateway-id'], $fromTime, $toTime);
    }

    /**
     * Method to get experiments of a particular time range
     * @param $inputs
     * @return array
     */
    public static function get_experiments_of_time_range($inputs)
    {
        $experimentStatistics = AdminUtilities::get_experiment_execution_statistics(
            strtotime($inputs["from-date"]) * 1000,
            strtotime($inputs["to-date"]) * 1000
        );
        $experiments = array();
        if ($inputs["status-type"] == "ALL") {
            $experiments = $experimentStatistics->allExperiments;
        }else if ($inputs["status-type"] == "COMPLETED") {
            $experiments = $experimentStatistics->completedExperiments;
        }else if ($inputs["status-type"] == "CREATED") {
            $experiments = $experimentStatistics->createdExperiments;
        }else if ($inputs["status-type"] == "RUNNING") {
            $experiments = $experimentStatistics->runningExperiments;
        } elseif ($inputs["status-type"] == "FAILED") {
            $experiments = $experimentStatistics->failedExperiments;
        } else if ($inputs["status-type"] == "CANCELED") {
            $experiments = $experimentStatistics->cancelledExperiments;
        }

        $expContainer = array();
        $expNum = 0;
        foreach ($experiments as $experiment) {
            $expValue = ExperimentUtilities::get_experiment_summary_values($experiment, true);
            $expContainer[$expNum]['experiment'] = $experiment;
            $expValue["editable"] = false;
            $expContainer[$expNum]['expValue'] = $expValue;
            $expNum++;
        }

        return $expContainer;
    }
}