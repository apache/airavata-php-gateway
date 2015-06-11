<?php

use Airavata\Model\Workspace\Gateway;

class AdminUtilities
{

    /**
     * To create a new gateway
     * @param $input
     * @return string
     */
    public static function addGateway($input)
    {
        $gateway = new Gateway();
        $gateway->gatewayId = $input["gatewayName"];
        $gateway->domain = $input["domain"];
        $gateway->gatewayName = $input["gatewayName"];
        $gateway->emailAddress = $input["admin-email"];
        return Airavata::addGateway($gateway);
    }

    /**
     * Method to get experiment execution statistics object
     * @param $fromTime
     * @param $toTime
     * @return \Airavata\Model\Workspace\Experiment\ExperimentStatistics
     */
    public static function getExperimentExecutionStatistics($fromTime, $toTime)
    {
        return Airavata::getExperimentStatistics(Config::get('pga_config.airavata')['gateway-id'], $fromTime, $toTime);
    }
}